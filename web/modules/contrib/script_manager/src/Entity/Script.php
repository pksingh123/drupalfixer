<?php

namespace Drupal\script_manager\Entity;

use Drupal\Core\Condition\ConditionPluginCollection;
use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;

/**
 * A script config entity.
 *
 * @ConfigEntityType(
 *   id = "script",
 *   label = @Translation("Script"),
 *   admin_permission = "administer scripts",
 *   handlers = {
 *     "list_builder" = "Drupal\script_manager\ScriptListBuilder",
 *     "access" = "Drupal\script_manager\Entity\ScriptAccessControlHandler",
 *     "form" = {
 *       "default" = "Drupal\script_manager\Form\ScriptForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   links = {
 *     "delete-form" = "/admin/people/roles/manage/{user_role}/delete",
 *     "edit-form" = "/admin/structure/scripts/manage/{script}",
 *     "collection" = "/admin/structure/scripts",
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "snippet",
 *     "position",
 *     "visibility"
 *   }
 * )
 */
class Script extends ConfigEntityBase implements ScriptInterface, EntityWithPluginCollectionInterface {

  /**
   * The script machine name.
   *
   * @var string
   */
  protected $id;

  /**
   * The human readable label.
   *
   * @var string
   */
  protected $label;

  /**
   * The JavaScript snippet.
   *
   * @var string
   */
  protected $snippet;

  /**
   * The position of the script.
   *
   * @var string
   */
  protected $position;

  /**
   * The visibility settings for this block.
   *
   * @var array
   */
  protected $visibility = [];

  /**
   * The condition plugins.
   *
   * @var \Drupal\Core\Condition\ConditionPluginCollection
   */
  protected $visibilityCollection;

  /**
   * {@inheritdoc}
   */
  public function getSnippet() {
    return $this->snippet;
  }

  /**
   * {@inheritdoc}
   */
  public function getPosition() {
    return $this->position;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return [
      'visibility' => $this->getVisibilityConditions(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getVisibilityConditions() {
    if (!isset($this->visibilityCollection)) {
      $this->visibilityCollection = new ConditionPluginCollection(\Drupal::service('plugin.manager.condition'), $this->get('visibility'));
    }
    return $this->visibilityCollection;
  }

}
