<?php

namespace Drupal\slick_views\Plugin\views\style;

use Drupal\blazy\Dejavu\BlazyStylePluginBase;
use Drupal\slick\SlickDefault;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The base class common for Slick style plugins.
 */
abstract class SlickViewsBase extends BlazyStylePluginBase {

  /**
   * The slick service manager.
   *
   * @var \Drupal\slick\SlickManagerInterface
   */
  protected $manager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->manager = $container->get('slick.manager');
    $instance->blazyManager = isset($instance->blazyManager) ? $instance->blazyManager : $container->get('blazy.manager');

    return $instance;
  }

  /**
   * Returns the slick manager.
   */
  public function manager() {
    return $this->manager;
  }

  /**
   * Returns the slick admin.
   */
  public function admin() {
    return \Drupal::service('slick.admin');
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = [];
    foreach (SlickDefault::extendedSettings() as $key => $value) {
      $options[$key] = ['default' => $value];
    }
    return $options + parent::defineOptions();
  }

  /**
   * Returns the defined scopes for the current form.
   */
  protected function getDefinedFormScopes(array $extra_fields = []) {
    // Pass the common field options relevant to this style.
    $fields = [
      'captions',
      'classes',
      'images',
      'layouts',
      'links',
      'overlays',
      'thumbnails',
      'thumb_captions',
      'titles',
    ];

    // Fetches the returned field definitions to be used to define form scopes.
    $fields = array_merge($fields, $extra_fields);
    $definition = $this->getDefinedFieldOptions($fields);

    // @todo remove _form for forms when Blazy 2.x has it.
    $options = [
      'fieldable_form',
      'grid_form',
      'id',
      'nav',
      'thumb_positions',
      'vanilla',
    ];

    foreach ($options as $key) {
      $definition[$key] = TRUE;
    }

    $definition['forms'] = ['fieldable' => TRUE, 'grid' => TRUE];
    $definition['opening_class'] = 'form--views';
    $definition['_views'] = TRUE;

    return $definition;
  }

  /**
   * Build the Slick settings form.
   */
  protected function buildSettingsForm(&$form, &$definition) {
    $count = count($definition['captions']);
    $definition['captions_count'] = $count;

    $this->admin()->buildSettingsForm($form, $definition);

    // @todo remove custom classes for Blazy 2.x and 1.x updates.
    $wide = $count > 2 ? ' form--wide form--caption-' . $count : ' form--caption-' . $count;
    $title = '<p class="form__header form__title">';
    $title .= $this->t('Check Vanilla if using content/custom markups, not fields. <small>See it under <strong>Format > Show</strong> section. Otherwise slick markups apply which require some fields added below.</small>');
    $title .= '</p>';

    $form['opening']['#markup'] = '<div class="form--slick form--views form--half form--vanilla has-tooltip' . $wide . '">' . $title;

    if (isset($form['image'])) {
      $form['image']['#description'] .= ' ' . $this->t('Use Blazy formatter to have it lazyloaded. Other supported Formatters: Colorbox, Intense, Responsive image, Video Embed Field, Youtube Field.');
    }

    if (isset($form['overlay'])) {
      $form['overlay']['#description'] .= ' ' . $this->t('Be sure to CHECK "<strong>Style settings > Use field template</strong>" _only if using Slick formatter for nested sliders, otherwise keep it UNCHECKED!');
    }

    // Bring in dots thumbnail effect normally used by Slick Image formatter.
    $form['thumbnail_effect'] = [
      '#type' => 'select',
      '#title' => $this->t('Dots thumbnail effect'),
      '#options' => [
        'hover' => $this->t('Hoverable'),
        'grid' => $this->t('Static grid'),
      ],
      '#empty_option' => $this->t('- None -'),
      '#description' => $this->t('Dependent on a Skin, Dots and Thumbnail image options. No asnavfor/ Optionset thumbnail is needed. <ol><li><strong>Hoverable</strong>: Dots pager are kept, and thumbnail will be hidden and only visible on dot mouseover, default to min-width 120px.</li><li><strong>Static grid</strong>: Dots are hidden, and thumbnails are displayed as a static grid acting like dots pager.</li></ol>Alternative to asNavFor aka separate thumbnails as slider.'),
      '#weight' => -100,
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function buildSettings() {
    // @todo move it into self::prepareSettings() post blazy:2.x.
    $this->options['item_id'] = 'slide';
    $this->options['namespace'] = 'slick';
    $settings = parent::buildSettings();

    // Prepare needed settings to work with.
    $settings['caption'] = array_filter($settings['caption']);
    $settings['nav'] = !$settings['vanilla'] && $settings['optionset_thumbnail'] && isset($this->view->result[1]);
    $settings['overridables'] = empty($settings['override']) ? array_filter($settings['overridables']) : $settings['overridables'];

    return $settings;
  }

  /**
   * Returns slick contents.
   */
  public function buildElements(array $settings, $rows) {
    $build   = [];
    $view    = $this->view;
    $item_id = $settings['item_id'];

    foreach ($rows as $index => $row) {
      $view->row_index = $index;

      $slide = [];
      $thumb = $slide[$item_id] = [];

      // Provides a potential unique thumbnail different from the main image.
      if (!empty($settings['thumbnail'])) {
        $thumbnail = $this->getFieldRenderable($row, 0, $settings['thumbnail']);
        if (isset($thumbnail['rendered']['#image_style'], $thumbnail['rendered']['#item']) && $item = $thumbnail['rendered']['#item']) {
          $uri = (($entity = $item->entity) && empty($item->uri)) ? $entity->getFileUri() : $item->uri;
          $settings['thumbnail_style'] = $thumbnail['rendered']['#image_style'];
          $settings['thumbnail_uri'] = empty($settings['thumbnail_style']) ? $uri : $this->manager->entityLoad($settings['thumbnail_style'], 'image_style')->buildUri($uri);
        }
      }

      $slide['settings'] = $settings;

      // Use Vanilla slick if so configured, ignoring Slick markups.
      if (!empty($settings['vanilla'])) {
        $slide[$item_id] = $view->rowPlugin->render($row);
      }
      else {
        // Otherwise, extra works. With a working Views cache, no big deal.
        $this->buildElement($slide, $row, $index);

        // Build thumbnail navs if so configured.
        if (!empty($settings['nav'])) {
          $thumb[$item_id] = empty($settings['thumbnail']) ? [] : $this->getFieldRendered($index, $settings['thumbnail']);
          $thumb['caption'] = empty($settings['thumbnail_caption']) ? [] : $this->getFieldRendered($index, $settings['thumbnail_caption']);

          $build['thumb']['items'][$index] = $thumb;
        }
      }

      if (!empty($settings['class'])) {
        $classes = $this->getFieldString($row, $settings['class'], $index);
        $slide['settings']['class'] = empty($classes[$index]) ? [] : $classes[$index];
      }

      if (empty($slide[$item_id]) && !empty($settings['image'])) {
        $slide[$item_id] = $this->getFieldRendered($index, $settings['image']);
      }

      $build['items'][$index] = $slide;
      unset($slide, $thumb);
    }

    unset($view->row_index);
    return $build;
  }

}
