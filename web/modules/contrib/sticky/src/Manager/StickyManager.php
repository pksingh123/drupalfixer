<?php

namespace Drupal\sticky\Manager;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class StickyManager
 *
 * @package Drupal\sticky\Manager.
 */
class StickyManager {

  protected $config;

  public function __construct(ConfigFactoryInterface $config) {
    $this->config = $config->get('sticky.settings');
  }

  /**
   * Returns JS settings.
   * @return array
   */
  public function getJsSettings() {

    return [
      'selector' => $this->config->get('selector'),
      'top_spacing' => $this->config->get('top_spacing'),
      'bottom_spacing' => $this->config->get('bottom_spacing'),
      'class_name' => $this->config->get('class_name'),
      'wrapper_class_name' => $this->config->get('wrapper_class_name'),
      'center' => $this->config->get('center'),
      'get_width_from' => $this->config->get('get_width_from'),
      'width_from_wrapper' => $this->config->get('width_from_wrapper'),
      'responsive_width' => $this->config->get('responsive_width'),
      'z_index' => $this->config->get('z_index'),
    ];
  }

}
