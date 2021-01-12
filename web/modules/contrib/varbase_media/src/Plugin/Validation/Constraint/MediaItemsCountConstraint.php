<?php

namespace Drupal\varbase_media\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Check number of media items.
 *
 * @Constraint(
 *   id = "MediaItemsCount",
 *   label = @Translation("Media items count", context = "Validation"),
 * )
 */
class MediaItemsCountConstraint extends Constraint {

  /**
   * Source field name.
   *
   * @var string
   */
  public $sourceFieldName;

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = 'At least one media item must exist.';

}
