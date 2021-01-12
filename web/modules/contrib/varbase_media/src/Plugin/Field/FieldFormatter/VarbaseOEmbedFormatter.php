<?php

namespace Drupal\varbase_media\Plugin\Field\FieldFormatter;

use Drupal\Core\Url;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\media\Plugin\Field\FieldFormatter\OEmbedFormatter;

/**
 * Plugin implementation of the 'oembed' formatter for Varbase.
 *
 * @internal
 *   This is an internal part of the oEmbed system and should only be used by
 *   oEmbed-related code in Varbase.
 *
 * @FieldFormatter(
 *   id = "varbase_oembed",
 *   label = @Translation("Varbase oEmbed content"),
 *   field_types = {
 *     "link",
 *     "string",
 *     "string_long",
 *   },
 * )
 */
class VarbaseOEmbedFormatter extends OEmbedFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $max_width = $this->getSetting('max_width');
    $max_height = $this->getSetting('max_height');

    foreach ($items as $delta => $item) {
      $main_property = $item->getFieldDefinition()->getFieldStorageDefinition()->getMainPropertyName();
      $value = $item->{$main_property};

      if (empty($value)) {
        continue;
      }

      $media = $item->getEntity();
      $provider = $media->field_provider->value;

      $url = Url::fromRoute('media.oembed_iframe', [], [
        'query' => [
          'url' => $value,
          'max_width' => $max_width,
          'max_height' => $max_height,
          'type' => "remote_video",
          'provider' => strtolower($provider),
          'hash' => $this->iFrameUrlHelper->getHash($value, $max_width, $max_height, $provider),
        ],
      ]);

      // Render videos and rich content in an iframe for security reasons.
      // @see: https://oembed.com/#section3
      $element[$delta] = [
        '#type' => 'html_tag',
        '#tag' => 'iframe',
        '#attributes' => [
          'src' => $url->toString(),
          'frameborder' => 0,
          'scrolling' => FALSE,
          'allowtransparency' => TRUE,
          'width' => $max_width,
          'height' => $max_height,
          'class' => ['media-oembed-content'],
        ],
        '#attached' => [
          'library' => [
            'media/oembed.formatter',
          ],
        ],
      ];

    }
    return $element;
  }

}
