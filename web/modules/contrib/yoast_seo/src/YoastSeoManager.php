<?php

namespace Drupal\yoast_seo;

use Drupal\Core\Extension\ModuleHandlerInterface;
use \Drupal\views\Views;
use \Drupal\Component\Utility\Html;
use \Symfony\Component\Yaml\Yaml;

/**
 * Class YoastSeoManager.
 *
 * @package Drupal\yoast_seo
 */
class YoastSeoManager {

  /**
   * Module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Target elements for Javascript.
   *
   * @var array
   */
  public static $jsTargets = [
    'wrapper_target_id'       => 'yoast-wrapper',
    'snippet_target_id'       => 'yoast-snippet',
    'output_target_id'        => 'yoast-output',
    'overall_score_target_id' => 'yoast-overall-score',
  ];

  /**
   * Constructor for YoastSeoManager.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler service.
   */
  public function __construct(ModuleHandlerInterface $module_handler) {
    $this->moduleHandler = $module_handler;
    $this->yoast_seo_field_manager = \Drupal::service('yoast_seo.field_manager');

    // Populate js target ids.
    foreach (self::$jsTargets as $js_target_name => $js_target_id) {
      if (!preg_match('/^js/', $js_target_id)) {
        self::$jsTargets[$js_target_name] = Html::getUniqueId($js_target_id);
      }
    }
  }

  /**
   * Attach the yoast seo fields to a target content type.
   *
   * @param string $entity_type
   *   Bundle.
   * @param array $bundle
   *   Entity type.
   */
  public function attachYoastSeoFields($entity_type, $bundle) {
    // Attach metatag field to the target content.
    $metatag_field = array(
      'field_name' => 'field_meta_tags',
      'field_label' => 'Meta tags',
      'storage_type' => 'metatag',
      'translatable' => TRUE,
    );
    $this->yoast_seo_field_manager->attachField($entity_type, $bundle, $metatag_field);

    // Attach yoast seo field to the target content type.
    $yoast_fields = [
      'field_yoast_seo' => array(
        'field_name' => 'field_yoast_seo',
        'field_label' => 'Real-time SEO',
        'storage_type' => 'yoast_seo',
        'translatable' => TRUE,
      ),
    ];
    foreach ($yoast_fields as $field) {
      $this->yoast_seo_field_manager->attachField($entity_type, $bundle, $field);
    }
  }

  /**
   * Delete the yoast seo fields from a target content type.
   *
   * @param string $entity_type
   *   Entity type.
   * @param string $bundle
   *   Bundle.
   */
  public function detachYoastSeoFields($entity_type, $bundle) {
    // Detach seo fields from the target content type.
    $yoast_fields = [
      'field_yoast_seo',
    ];

    foreach ($yoast_fields as $field_name) {
      $this->yoast_seo_field_manager->detachField($entity_type, $bundle, $field_name);
    }
  }

  /**
   * Returns an array of available bundles Yoast SEO can be enabled for.
   *
   * @param string $entity_type
   *   The entity.
   *
   * @return array
   *   A list of available bundles as $id => $label.
   */
  public function getAvailableBundles($entity_type = 'node') {
    $options        = array();
    // Retrieve the bundles the entity type contains.
    $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($entity_type);
    foreach ($bundles as $bundle_id => $bundle_metadata) {
      $options[$bundle_id] = $bundle_metadata['label'];
    }

    return $options;
  }

  /**
   * Returns an array of bundles Yoast SEO has been enabled for.
   *
   * @param string $entity_type
   *   The entity.
   *
   * @return array
   *   A list of enabled bundles as $id => $label.
   */
  public function getEnabledBundles($entity_type = 'node') {
    $enabled_bundles         = array();
    $yoast_seo_field_manager = \Drupal::service('yoast_seo.field_manager');

    // Get the available bundles Yoast SEO supports.
    $bundles = $this->getAvailableBundles($entity_type);

    // Retrieve the bundles for which Yoast SEO has already been enabled for.
    foreach ($bundles as $bundle_id => $bundle_label) {
      if ($yoast_seo_field_manager->isAttached($entity_type, $bundle_id, 'field_yoast_seo')) {
        $enabled_bundles[] = $bundle_id;
      }
    }

    return $enabled_bundles;
  }

  /**
   * Attach a field handler for yoast seo in the content view.
   */
  public function attachFieldHandlerToContentView() {
    $content_view = Views::getView('content');

    if ($content_view) {
      $display_id = 'page_1';

      $handlers = $content_view->getHandlers('field', $display_id);
      if (!isset($handlers['field_yoast_seo'])) {
        $content_view->addHandler(
          $display_id,
          'field',
          'node__field_yoast_seo',
          'field_yoast_seo',
          [
            'type' => 'yoastseo_formatter',
          ],
          'field_yoast_seo'
        );
        $content_view->save();
      }
    }
  }

  /**
   * Detach the field handler for yoast seo from the content view.
   */
  public function detachFieldHandlerFromContentView() {
    $content_view = Views::getView('content');

    if ($content_view) {
      $display_id = 'page_1';

      $handlers = $content_view->getHandlers('field', $display_id);
      if (isset($handlers['field_yoast_seo'])) {
        $content_view->removeHandler($display_id, 'field', 'field_yoast_seo');
        $content_view->save();
      }
    }
  }

  /**
   * Set configuration for js target elements.
   *
   * @param array $elt
   *   The element on which to attach the configuration.
   *
   * @return array
   *   The same element passed, with the new configuration attached.
   */
  public function setTargetsConfiguration(&$elt) {
    foreach (self::$jsTargets as $js_target_name => $js_target_id) {
      $elt['#attached']['drupalSettings']['yoast_seo']['targets'][$js_target_name] = $js_target_id;
    }
    return $elt;
  }

  /**
   * Set general configuration for yoast seo js.
   *
   * Set
   * - language
   * - options (analyzer, snippet_preview)
   * - base_root.
   *
   * @param array $elt
   *   The element on which to attach the configuration.
   *
   * @return array
   *   The same element passed, with the new configuration attached.
   */
  public function setGeneralConfiguration(&$elt) {
    // Set the language code.
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $elt['#attached']['drupalSettings']['yoast_seo']['language'] = $language;

    // General Yoast SEO js config.
    $elt['#attached']['drupalSettings']['yoast_seo']['analyzer'] = TRUE;
    $elt['#attached']['drupalSettings']['yoast_seo']['snippet_preview'] = TRUE;

    // Base root.
    global $base_root;
    $elt['#attached']['drupalSettings']['yoast_seo']['base_root'] = $base_root;

    // Cookie key where to store data.
    $elt['#attached']['drupalSettings']['yoast_seo']['cookie_data_key'] = 'yoastseo.metatags';

    return $elt;
  }

  /**
   * Set configuration for score to status rules.
   *
   * @param array $elt
   *   The element on which to attach the configuration.
   *
   * @return array
   *   The same element passed, with the new configuration attached.
   */
  public function setScoreToStatusRulesConfiguration(&$elt) {
    $score_to_status_rules = $this->getScoreToStatusRules();
    // Score statuses.
    $elt['#attached']['drupalSettings']['yoast_seo']['score_status'] = $score_to_status_rules;
    return $elt;
  }

  /**
   * Get configuration from Yaml file.
   *
   * @return mixed
   *    Configuration details will be returned.
   */
  public function getConfiguration() {
    $conf = Yaml::parse(
      file_get_contents(
        drupal_get_path('module', 'yoast_seo') . '/config/yoast_seo.yml'
      )
    );
    return $conf;
  }

  /**
   * Get rules to convert a score into a status, from the config file.
   *
   * @return mixed
   *    Score to status rules will be returned.
   */
  public function getScoreToStatusRules() {
    $score_to_status_rules = $this->getConfiguration()['score_to_status_rules'];
    ksort($score_to_status_rules);
    return $score_to_status_rules;
  }

  /**
   * Get Markup for the snippet editor.
   *
   * @return string
   *   HTML Markup of the snippet editor.
   */
  public function getSnippetEditorMarkup() {
    // Get template for the snippet.
    $snippet_tpl = [
      '#theme' => 'yoast_snippet',
      '#wrapper_target_id' => self::$jsTargets['wrapper_target_id'],
      '#snippet_target_id' => self::$jsTargets['snippet_target_id'],
      '#output_target_id' => self::$jsTargets['output_target_id'],
    ];
    return \Drupal::service('renderer')->renderRoot($snippet_tpl);
  }

  /**
   * Get Markup for the overall score.
   *
   * @param int $score
   *   The default score to display.
   *
   * @return string
   *   HTML Markup of the overall score.
   */
  public function getOverallScoreMarkup($score = 0) {
    $template = 'overall_score';
    $yoast_seo_manager = \Drupal::service('yoast_seo.manager');

    // Get template for the snippet.
    $overall_score_tpl = [
      '#theme' => $template,
      '#overall_score_target_id' => self::$jsTargets['overall_score_target_id'],
      '#overall_score' => $this->getScoreStatus($score),
    ];

    return \Drupal::service('renderer')->renderRoot($overall_score_tpl);
  }

  /**
   * Get the status for a given score.
   *
   * @param int $score
   *   Score in points.
   *
   * @return string
   *   Status corresponding to the score.
   */
  public function getScoreStatus($score) {
    $rules = $this->getScoreToStatusRules();
    $default = $rules['default'];
    unset($rules['default']);

    foreach ($rules as $status => $status_rules) {
      $min_max_isset = isset($status_rules['min']) && isset($status_rules['max']);
      if (isset($status_rules['equal']) && $status_rules['equal'] == $score) {
        return $status;
      }
      elseif ($min_max_isset && $score > $status_rules['min'] && $score <= $status_rules['max']) {
        return $status;
      }
    }

    return $default;
  }

}
