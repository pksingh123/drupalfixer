<?php

namespace Drupal\varbase_media\Plugin\media\Source;

use Drupal\media\MediaInterface;
use Drupal\media\MediaSourceBase;
use Drupal\media\MediaSourceEntityConstraintsInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

/**
 * Provides media type plugin for Gallery.
 *
 * @MediaSource(
 *   id = "gallery",
 *   label = @Translation("Gallery"),
 *   description = @Translation("Provides business logic and metadata for gallery."),
 *   default_thumbnail_filename = "gallery.png",
 *   allowed_field_types = {"entity_reference"},
 * )
 */
class VarbaseMediaGallery extends MediaSourceBase implements MediaSourceEntityConstraintsInterface {

  /**
   * {@inheritdoc}
   */
  public function getMetadataAttributes() {
    $attributes = [
      'length' => $this->t('Gallery length'),
    ];

    return $attributes;
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadata(MediaInterface $media, $name) {
    $source_field = $this->configuration['source_field'];

    switch ($name) {
      case 'default_name':
        $length = $this->getMetadata($media, 'length');
        if (!empty($length)) {
          return $this->formatPlural($length,
            '1 media item, created on @date',
            '@count media items, created on @date',
            [
              '@date' => \Drupal::service('date.formatter')
                ->format($media->getCreatedTime(), 'custom', DateTimeItemInterface::DATETIME_STORAGE_FORMAT),
            ]);
        }
        return parent::getMetadata($media, 'default_name');

      case 'length':
        return $media->{$source_field}->count();

      case 'thumbnail_uri':
        return parent::getMetadata($media, 'thumbnail_uri');

      default:
        return parent::getMetadata($media, $name);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityConstraints() {
    $source_field = $this->configuration['source_field'];

    return ['MediaItemsCount' => ['sourceFieldName' => $source_field]];
  }

}
