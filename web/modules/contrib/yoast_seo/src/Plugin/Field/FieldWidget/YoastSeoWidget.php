<?php

namespace Drupal\yoast_seo\Plugin\Field\FieldWidget;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\yoast_seo\YoastSeoManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Advanced widget for yoast_seo field.
 *
 * @FieldWidget(
 *   id = "yoast_seo_widget",
 *   label = @Translation("Real-time SEO form"),
 *   field_types = {
 *     "yoast_seo"
 *   }
 * )
 */
class YoastSeoWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * Instance of YoastSeoManager service.
   */
  protected $yoastSeoManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('entity_field.manager'),
      $container->get('yoast_seo.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, EntityFieldManagerInterface $entity_field_manager, YoastSeoManager $manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->entityFieldManager = $entity_field_manager;
    $this->yoastSeoManager = $manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = [
      'body' => 'body',
    ];

    return $settings + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = $this->t('Body: @body', ['@body' => $this->getSetting('body')]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = [];
    /** @var EntityFormDisplayInterface $form_display */
    $form_display = $form_state->getFormObject()->getEntity();
    $entity_type = $form_display->getTargetEntityTypeId();
    $bundle = $form_display->getTargetBundle();
    $fields = $this->entityFieldManager->getFieldDefinitions($entity_type, $bundle);
    $text_field_types = ['text_with_summary', 'text_long', 'string_long'];
    $text_fields = [];

    if (empty($fields)) {
      return $elements;
    }

    foreach ($fields as $field_name => $field) {
      if (in_array($field->getType(), $text_field_types)) {
        $text_fields[$field_name] = $field->getLabel() . ' (' . $field_name . ')';
      }
    }

    $element['body'] = [
      '#type' => 'select',
      '#title' => $this->t('Body'),
      '#required' => TRUE,
      '#description' => $this->t('Select a field which is used as the body field.'),
      '#options' => $text_fields,
      '#default_value' => $this->getSetting('body'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $form['#yoast_settings'] = $this->getSettings();

    // Create the form element.
    $element['yoast_seo'] = array(
      '#type' => 'details',
      '#title' => $this->t('Real-time SEO for drupal'),
      '#open' => TRUE,
      '#attached' => array(
        'library' => array(
          'yoast_seo/yoast_seo_core',
          'yoast_seo/yoast_seo_admin',
        ),
      ),
    );

    $element['yoast_seo']['focus_keyword'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Focus keyword'),
      '#default_value' => isset($items[$delta]->focus_keyword) ? $items[$delta]->focus_keyword : NULL,
      '#description' => $this->t("Pick the main keyword or keyphrase that this post/page is about."),
    );

    $element['yoast_seo']['status'] = array(
      '#type' => 'hidden',
      '#title' => $this->t('Real-time SEO status'),
      '#default_value' => isset($items[$delta]->status) ? $items[$delta]->status : NULL,
      '#description' => $this->t("The SEO status in points."),
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as &$value) {
      $value['status']        = $value['yoast_seo']['status'];
      $value['focus_keyword'] = $value['yoast_seo']['focus_keyword'];
    }
    return $values;
  }

}
