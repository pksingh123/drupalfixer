<?php

namespace Drupal\varbase_media\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\InstallStorage;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides form for managing Varbase Media settings form.
 */
class VarbaseMediaSettingsForm extends ConfigFormBase {

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a new Varbase Media settings form.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler) {
    parent::__construct($config_factory);
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('module_handler')
    );
  }

  /**
   * Get the from ID.
   */
  public function getFormId() {
    return 'varbase_media_settings';
  }

  /**
   * Get the editable config names.
   */
  protected function getEditableConfigNames() {
    return ['varbase_media.settings'];
  }

  /**
   * Build the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('varbase_media.settings');

    // $blazy_settings_link = Link::fromTextAndUrl($this->t('Blazy UI'),
    // new Url('blazy.settings'))->toString();
    // ------------------------------------------------------------------.
    // Changed blazy settings link to work if the Blazy UI module is disabled.
    $blazy_settings_link = base_path() . "admin/config/media/blazy";

    $form['use_blazy_blurry'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use blurred image loading effect for Lazy-loaded images'),
      '#description' => $this->t('Varbase uses Blazy to lazy-load offscreen images. This setting enhances Varbase to lazy-load images with a low-res blurred image effect. Switching this setting off (<em>not recommended</em>) will fallback to Blazy pixel loader as configured in <a href="@blazy_settings_link">Blazy UI</a> Note that you’ll need to enable Blazy UI module to configure it.', ['@blazy_settings_link' => $blazy_settings_link]),
      '#default_value' => $config->get('use_blazy_blurry'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Submit Form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Save Varbase Media settings.
    $use_blazy_blurry = (bool) $form_state->getValue('use_blazy_blurry');
    $this->config('varbase_media.settings')
      ->set('use_blazy_blurry', $use_blazy_blurry)
      ->save();

    // Have the Blazy Blurry image style in the active config.
    if ($use_blazy_blurry) {
      $module_path = $this->moduleHandler->getModule('varbase_media')->getPath();
      $optional_install_path = $module_path . '/' . InstallStorage::CONFIG_OPTIONAL_DIRECTORY;

      $image_style_config_path = $optional_install_path . '/' . 'image.style.blazy_blurry.yml';
      $image_style_config_content = file_get_contents($image_style_config_path);
      $image_style_config_data = (array) Yaml::parse($image_style_config_content);
      $image_style_config_factory = $this->configFactory->getEditable('image.style.blazy_blurry');
      $image_style_config_factory->setData($image_style_config_data)->save(TRUE);
    }

    parent::submitForm($form, $form_state);
  }

}
