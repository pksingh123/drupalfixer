<?php

namespace Drupal\blazy;

/**
 * Provides extra utilities to work with core Media.
 *
 * This class makes it possible to have a mixed display of all media entities,
 * useful for Blazy Grid, Slick Carousel, GridStack contents as mixed media.
 * This approach is alternative to regular preprocess overrides, still saner
 * than iterating over unknown like template_preprocess_media_entity_BLAH, etc.
 *
 * @internal
 *   This is an internal part of the Blazy system and should only be used by
 *   blazy-related code in Blazy module. Media integration is being reworked.
 *
 * @todo rework this for core Media, and refine for theme_blazy(). One big TODO
 * for the next releases is to replace ImageItem references into just $settings.
 */
interface BlazyMediaInterface {

  /**
   * Builds the media field which is not understood by theme_blazy().
   *
   * @param object $media
   *   The media being rendered.
   * @param array $settings
   *   The contextual settings array.
   *
   * @return array|bool
   *   The renderable array of the media field, or false if not applicable.
   *
   * @todo make it non-static method.
   */
  public static function build($media, array $settings = []);

  /**
   * Returns a field item/ content to be wrapped by theme_blazy().
   *
   * @param array $field
   *   The source renderable array $field.
   *
   * @return array
   *   The renderable array of the media item to be wrapped by theme_blazy().
   *
   * @todo make it non-static method.
   */
  public static function wrap(array $field = []);

  /**
   * Extracts image from File for the main background/stage, image or video.
   *
   * Main image can be separate image item from video thumbnail for highres.
   * Fallback to default thumbnail if any, which has no file API. This used to
   * be for non-media File Entity Reference at 1.x, things changed since then.
   * This is no longer needed nor functional since BlazyFileFormatter is already
   * deprecated for BlazyMediaFormatter.
   *
   * @param array $element
   *   The element array might contain item and settings.
   * @param object $entity
   *   The file entity or entityreference which might have image item.
   *
   * @todo deprecated in blazy:8.x-2.0 and is removed from blazy:8.x-2.1+. Use
   *   \Drupal\blazy\Plugin\Field\FieldFormatter\BlazyMediaFormatter instead.
   * @see https://www.drupal.org/node/3103018
   * @see \Drupal\blazy\Dejavu\BlazyEntityMediaBase::buildElement
   */
  public static function imageItem(array &$element, $entity);

}
