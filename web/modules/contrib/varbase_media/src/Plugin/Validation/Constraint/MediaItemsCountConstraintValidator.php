<?php

namespace Drupal\varbase_media\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the MediaItemsCount constraint.
 */
class MediaItemsCountConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    if (!isset($value)) {
      return;
    }

    if ($value->get($constraint->sourceFieldName)->isEmpty()) {
      $this->context->addViolation($constraint->message);
    }
  }

}
