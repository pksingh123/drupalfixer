<?php

namespace Drupal\script_manager\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Interface ScriptInterface.
 */
interface ScriptInterface extends ConfigEntityInterface {

  /**
   * Positioned at the top of the page.
   */
  const POSITION_TOP = 'top';

  /**
   * Positioned at the bottom of the page.
   */
  const POSITION_BOTTOM = 'bottom';

  /**
   * A position not visible on the page.
   */
  const POSITION_HIDDEN = 'hidden';

  /**
   * Get the JavaScript snippet for the entity.
   *
   * @return string
   *   The snippet.
   */
  public function getSnippet();

  /**
   * Get the position of the snippet.
   *
   * @return string
   *   The position of the script.
   */
  public function getPosition();

  /**
   * Get the visibility conditions.
   *
   * @return \Drupal\Core\Condition\ConditionPluginCollection
   *   An array of plugin instances.
   */
  public function getVisibilityConditions();

}
