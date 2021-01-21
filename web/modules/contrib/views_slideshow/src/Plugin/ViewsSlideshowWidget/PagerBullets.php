<?php

namespace Drupal\views_slideshow\Plugin\ViewsSlideshowWidget;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views_slideshow\ViewsSlideshowWidgetBase;

/**
 * Provides a pager using bullets.
 *
 * @ViewsSlideshowWidget(
 *   id = "views_slideshow_pager_bullets",
 *   type = "views_slideshow_pager",
 *   label = @Translation("Bullets"),
 * )
 */
class PagerBullets extends ViewsSlideshowWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'views_slideshow_pager_bullets_hover' => ['default' => 0],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    // Add field to see if they would like to activate slide and pause on pager
    // hover.
    $form['views_slideshow_pager_bullets_hover'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Activate Slide and Pause on Pager Hover'),
      '#default_value' => $this->getConfiguration()['views_slideshow_pager_bullets_hover'],
      '#description' => $this->t('Should the slide be activated and paused when hovering over a pager item.'),
      '#states' => [
        'visible' => [
          ':input[name="' . $this->getConfiguration()['dependency'] . '[enable]"]' => ['checked' => TRUE],
          ':input[name="' . $this->getConfiguration()['dependency'] . '[type]"]' => ['value' => 'views_slideshow_pager_bullets'],
        ],
      ],
    ];

    return $form;
  }

}
