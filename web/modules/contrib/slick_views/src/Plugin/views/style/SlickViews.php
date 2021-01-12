<?php

namespace Drupal\slick_views\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;

/**
 * Slick style plugin.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "slick",
 *   title = @Translation("Slick Carousel"),
 *   help = @Translation("Display the results in a Slick Carousel."),
 *   theme = "slick_wrapper",
 *   register_theme = FALSE,
 *   display_types = {"normal"}
 * )
 */
class SlickViews extends SlickViewsBase {

  /**
   * Overrides parent::buildOptionsForm().
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $definition = $this->getDefinedFormScopes();
    $this->buildSettingsForm($form, $definition);
  }

  /**
   * Overrides StylePluginBase::render().
   */
  public function render() {
    $settings = $this->buildSettings();

    $elements = [];
    foreach ($this->renderGrouping($this->view->result, $settings['grouping']) as $rows) {
      $build = $this->buildElements($settings, $rows);

      // Supports Blazy multi-breakpoint images if using Blazy formatter.
      $settings['first_image'] = isset($rows[0]) ? $this->getFirstImage($rows[0]) : [];

      $build['settings'] = $settings;

      $elements = $this->manager->build($build);
      unset($build);
    }
    return $elements;
  }

}
