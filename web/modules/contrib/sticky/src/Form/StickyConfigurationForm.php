<?php

namespace Drupal\sticky\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * This class creates the Sticky configuration form.
 *
 * @package Drupal\sticky\Form
 */
class StickyConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sticky_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['sticky.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $config = $this->config('sticky.settings');

    $form['active_on'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Active on'),
    ];

    $form['active_on']['selector'] = [
      '#type' => 'textfield',
      '#title' => $this->t('DOM Selector'),
      '#default_value' => ($config->get('selector')) ? $config->get('selector') : '.menu--main',
      '#description' => $this->t('The selector that defines the element should become Sticky. For example .menu--main, .header-wrapper or #footer'),
    ];

    $form['settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Javascript settings'),
    ];

    $form['settings']['top_spacing'] = [
      '#type' => 'number',
      '#title' => $this->t('Top spacing'),
      '#default_value' => ($config->get('top_spacing')) ? $config->get('top_spacing') : 0,
      '#description' => $this->t("Pixels between the page top and the element's top."),
    ];

    $form['settings']['bottom_spacing'] = [
      '#type' => 'number',
      '#title' => $this->t('Bottom spacing'),
      '#default_value' => ($config->get('bottom_spacing')) ? $config->get('bottom_spacing') : 0,
      '#description' => $this->t("Pixels between the page bottom and the element's bottom."),
    ];

    $form['settings']['class_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Class name'),
      '#default_value' => ($config->get('class_name')) ? $config->get('class_name') : 'is-sticky',
      '#description' => $this->t("CSS class added to the element's wrapper when 'sticked'."),
    ];

    $form['settings']['wrapper_class_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Wrapper class name'),
      '#default_value' => ($config->get('wrapper_class_name')) ? $config->get('wrapper_class_name') : 'sticky-wrapper',
      '#description' => $this->t('CSS class added to the wrapper.'),
    ];

    $form['settings']['center'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Center'),
      '#default_value' => ($config->get('center')) ? $config->get('center') : FALSE,
      '#description' => $this->t('Boolean determining whether the sticky element should be horizontally centered in the page.'),
    ];

    $form['settings']['get_width_from'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Get width from'),
      '#default_value' => ($config->get('get_width_from')) ? $config->get('get_width_from') : '',
      '#description' => $this->t('Selector of element referenced to set fixed width of "sticky" element.'),
    ];

    $form['settings']['width_from_wrapper'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Width from wrapper'),
      '#default_value' => ($config->get('width_from_wrapper')) ? $config->get('width_from_wrapper') : TRUE,
      '#description' => $this->t("Boolean determining whether width of the 'sticky' element should be updated to match the wrapper's width. Wrapper is a placeholder for 'sticky' element while it is fixed (out of static elements flow), and its width depends on the context and CSS rules. Works only as long getWidthForm isn't set."),
    ];

    $form['settings']['responsive_width'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Responsive width'),
      '#default_value' => ($config->get('responsive_width')) ? $config->get('responsive_width') : FALSE,
      '#description' => $this->t('Boolean determining whether widths will be recalculated on window resize (using getWidthfrom).'),
    ];

    $form['settings']['z_index'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Z-index'),
      '#default_value' => ($config->get('z_index')) ? $config->get('z_index') : 'auto',
      '#description' => $this->t('Controls z-index of the sticked element.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues();

    $this->config('sticky.settings')
      ->set('selector', $values['selector'])
      ->set('top_spacing', $values['top_spacing'])
      ->set('bottom_spacing', $values['bottom_spacing'])
      ->set('class_name', $values['class_name'])
      ->set('wrapper_class_name', $values['wrapper_class_name'])
      ->set('center', $values['center'])
      ->set('get_width_from', $values['get_width_from'])
      ->set('width_from_wrapper', $values['width_from_wrapper'])
      ->set('responsive_width', $values['responsive_width'])
      ->set('z_index', $values['z_index'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
