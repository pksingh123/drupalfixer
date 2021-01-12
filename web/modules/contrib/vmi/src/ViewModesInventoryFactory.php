<?php

namespace Drupal\vmi;

use Symfony\Component\Yaml\Yaml;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * View Modes Inventory Factory.
 */
class ViewModesInventoryFactory implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs the View Modes Inventory Factory object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The translation service. for form alters.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, TranslationInterface $translation, ModuleHandlerInterface $module_handler) {
    $this->configFactory = $config_factory;
    $this->stringTranslation = $translation;
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('string_translation'),
      $container->get('module_handler')
    );
  }

  /**
   * Get data from view_modes.list.yml file.
   *
   * @return array
   *   Data array for the list of default view modes.
   *
   * @throws Exception
   */
  public function getViewModesList() {
    $module_path = $this->moduleHandler->getModule('vmi')->getPath();
    $vmi_filename = DRUPAL_ROOT . '/' . $module_path . '/src/assets/view_modes.list.vmi.yml';

    if (is_file($vmi_filename)) {
      $vmi_list = (array) Yaml::parse(file_get_contents($vmi_filename));
      return $vmi_list;
    }
    else {
      $lookup_message = $this->t('View modes inventory layouts list file does not exist!');
      throw new \Exception($lookup_message);
    }
  }

  /**
   * Get get data from layouts.mapping.yml file.
   *
   * @return array
   *   Data array for the default mapping layouts with view modes.
   *
   * @throws Exception
   */
  public function getLayoutsMapping() {
    $module_path = $this->moduleHandler->getModule('vmi')->getPath();
    $vmi_layout_filename = DRUPAL_ROOT . '/' . $module_path . '/src/assets/layouts.mapping.vmi.yml';

    if (is_file($vmi_layout_filename)) {
      $vmi_layout_list = (array) Yaml::parse(file_get_contents($vmi_layout_filename));
      return $vmi_layout_list;
    }
    else {
      $lookup_message = $this->t('View modes inventory layouts list file does not exist!');
      throw new \Exception($lookup_message);
    }
  }

  /**
   * Map a view mode with a layout and default configuration template.
   *
   * @param string $selected_view_mode
   *   Selected view mode in the custom display settings form.
   * @param string $default_mapped_layout
   *   Default mapped layout.
   * @param string $entity_type
   *   Entity type like node, block, user.
   * @param string $bundle_name
   *   Bundle name.
   * @param string $config_template_file
   *   Config template file name.
   * @param string $config_name
   *   Config name to map to.
   */
  public function mapViewModeWithLayout($selected_view_mode, $default_mapped_layout, $entity_type, $bundle_name, $config_template_file, $config_name) {

    // Replace CONTENT_TYPE_NAME with the bundle name for the config name.
    $real_config_name = str_replace('CONTENT_TYPE_NAME', $bundle_name, $config_name);

    $view_mode_config = $this->configFactory->getEditable($real_config_name);

    // Load the config template.
    $module_path = $this->moduleHandler->getModule('vmi')->getPath();
    $full_config_template_file = DRUPAL_ROOT . '/' . $module_path . $config_template_file;
    $config_template_content = file_get_contents($full_config_template_file);

    // Replace CONTENT_TYPE_NAME with the bundle name in the config template.
    $real_config_template_content = str_replace('CONTENT_TYPE_NAME', $bundle_name, $config_template_content);

    // Parse real config template conten to data.
    $real_config_template_content_data = (array) Yaml::parse($real_config_template_content);

    // Set and save new message value.
    $view_mode_config->setData($real_config_template_content_data)->save();

  }

}
