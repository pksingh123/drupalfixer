<?php

namespace Drupal\entity_browser_generic_embed\Plugin\EntityBrowser\Widget;

use Drupal\Core\Field\FieldFilteredMarkup;
use Drupal\Core\Form\FormStateInterface;

/**
 * An Entity Browser widget for creating media entities from embed codes.
 *
 * @EntityBrowserWidget(
 *   id = "embed_code",
 *   label = @Translation("Embed Code"),
 *   description = @Translation("Allows creation of media entities from embed codes."),
 * )
 */
class EmbedCode extends EntityFormProxy {

  /**
   * {@inheritdoc}
   */
  public function getForm(array &$original_form, FormStateInterface $form_state, array $additional_widget_parameters) {
    $form = parent::getForm($original_form, $form_state, $additional_widget_parameters);

    $form['input'] = [
      '#type' => 'textarea',
      '#placeholder' => $this->t('Enter a URL...'),
      '#attributes' => [
        'class' => ['keyup-change'],
      ],
      '#ajax' => [
        'event' => 'change',
        'wrapper' => 'entity',
        'method' => 'html',
        'callback' => [static::class, 'ajax'],
      ],
      "#description" => $this->getEmbedDescription($form_state),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(array &$form, FormStateInterface $form_state) {
    $value = trim($this->getInputValue($form_state));

    if ($value) {
      parent::validate($form, $form_state);
    }
    else {
      $form_state->setError($form['widget'], $this->t('You must enter a URL or embed code.'));
    }
  }

  /**
   * Get Embed Description.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The Status of the form.
   *
   * @return string
   *   Description Markup.
   */
  public function getEmbedDescription(FormStateInterface $form_state) {
    // Embed Description.
    $embedDescription = "<h5>" . $this->t("You can embed any of the following media by pasting a single complete URL:") . "</h5>";
    $embedDescription .= "<ul>";

    // Get list of media types.
    $mediaTypes = $this->entityTypeManager->getStorage('media_type')->loadMultiple();

    // List of media files Sources, which we do not want to show at embed.
    $mediaFileSources = ['Drupal\media\Plugin\media\Source\File',
      'Drupal\media\Plugin\media\Source\Image',
      'Drupal\media\Plugin\media\Source\AudioFile',
      'Drupal\media\Plugin\media\Source\VideoFile',
    ];

    // Get list of allowed media type bundles.
    $allowedBundles = $this->getAllowedBundles($form_state);

    // If the target have  a list of allowd bundles.
    if (isset($allowedBundles) && is_array($allowedBundles) && count($allowedBundles) > 0) {

      foreach ($mediaTypes as $mediaType) {
        $mediaTypeSource = get_class($mediaType->getSource());

        if (isset($mediaTypeSource) && !in_array($mediaTypeSource, $mediaFileSources)) {

          if (in_array($mediaType->id(), $allowedBundles)) {
            $embedDescription .= "<li>";
            $embedDescription .= $mediaType->label();

            $source_configuration = $mediaType->getPluginCollections();

            if (isset($source_configuration['source_configuration'])) {
              $configuration = $source_configuration['source_configuration']->getConfiguration();

              if (isset($configuration['providers'])
                  && is_array($configuration['providers'])
                  && count($configuration['providers']) > 0) {

                $embedDescription .= " (" . implode(', ', $configuration['providers']) . ")";
              }
            }

            $embedDescription .= "</li>";
          }
        }
      }
    }
    else {

      foreach ($mediaTypes as $mediaType) {
        $mediaTypeSource = get_class($mediaType->getSource());

        if (isset($mediaTypeSource) && !in_array($mediaTypeSource, $mediaFileSources)) {

          $embedDescription .= "<li>";
          $embedDescription .= $mediaType->label();

          $source_configuration = $mediaType->getPluginCollections();

          if (isset($source_configuration['source_configuration'])) {
            $configuration = $source_configuration['source_configuration']->getConfiguration();

            if (isset($configuration['providers'])
                && is_array($configuration['providers'])
                && count($configuration['providers']) > 0) {

              $embedDescription .= " (" . implode(', ', $configuration['providers']) . ")";
            }
          }

          $embedDescription .= "</li>";
        }
      }
    }

    $embedDescription .= "</ul>";

    return FieldFilteredMarkup::create($embedDescription);
  }

}
