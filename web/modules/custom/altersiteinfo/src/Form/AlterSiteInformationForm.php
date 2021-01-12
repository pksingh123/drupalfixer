<?php

namespace Drupal\altersiteinfo\Form;

// Classes referenced in this class:
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;
// This is the form being extended:
use Drupal\system\Form\SiteInformationForm;

/**
 * Configure site information settings for this site.
 */
class AlterSiteInformationForm extends SiteInformationForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve the system.site configuration

    $site_config = $this->config('system.site');

    // Get the original form from the class we are extending
    $form = parent::buildForm($form, $form_state);

    // Add a textarea to the site information section of the form for the description
    $form['site_information']['siteapikey'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Site Api Key'),
        // The default value is the new value we added to our configuration
        '#default_value' => $site_config->get('siteapikey') ? $site_config->get('siteapikey') : 'No API Key yet',
        '#description' => $this->t('The enter the site api key of the site'),
    );
    
    $form['actions']['submit']['#value'] = t('Update Configuration');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('system.site');

    // The siteapikey is retrieved from the submitted form values
    // and saved to the 'siteapikey' element of the system.site configuration.
    $siteapikey = $form_state->getValue('siteapikey');
    if (!empty($siteapikey) && $siteapikey != 'No API Key yet') {
      
      drupal_set_message("Site API Key has been saved " . $siteapikey);
      
      $config->set('siteapikey', $siteapikey);
      // Save the configuration
      $config->save();
    }

    // Pass the remaining values off to the parent form that is being extended,
    // so that that the parent form can process the values.
    parent::submitForm($form, $form_state);
  }

}
