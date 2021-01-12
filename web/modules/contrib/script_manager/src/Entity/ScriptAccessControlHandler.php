<?php

namespace Drupal\script_manager\Entity;

use Drupal\Component\Plugin\ContextAwarePluginInterface;
use Drupal\Component\Plugin\Exception\ContextException;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Condition\ConditionAccessResolverTrait;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Plugin\Context\ContextHandlerInterface;
use Drupal\Core\Plugin\Context\ContextRepositoryInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Access control handler for script entities.
 */
class ScriptAccessControlHandler extends EntityAccessControlHandler implements EntityHandlerInterface {

  use ConditionAccessResolverTrait;

  /**
   * The context handler.
   *
   * @var \Drupal\Core\Plugin\Context\ContextHandlerInterface
   */
  protected $contextHandler;

  /**
   * The context repository.
   *
   * @var \Drupal\Core\Plugin\Context\ContextRepositoryInterface
   */
  protected $contextRepository;

  /**
   * Constructs the access control handler.
   */
  public function __construct(EntityTypeInterface $entity_type, ContextHandlerInterface $context_handler, ContextRepositoryInterface $context_repository) {
    parent::__construct($entity_type);
    $this->contextHandler = $context_handler;
    $this->contextRepository = $context_repository;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('context.handler'),
      $container->get('context.repository')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\script_manager\Entity\Script $entity */
    if ($operation !== 'view') {
      return parent::checkAccess($entity, $operation, $account);
    }

    try {
      $conditions = $this->getPreparedConditions($entity);
    }
    catch (ContextException $e) {
      // Following core blocks convention, access is uncacheable when context
      // is missing.
      return AccessResult::forbidden()->setCacheMaxAge(0);
    }

    $access = $this->resolveConditions($conditions, 'and') !== FALSE ? AccessResult::allowed() : AccessResult::forbidden();

    // Add dependencies on all of the condition and entity cachability metadata.
    $access->addCacheableDependency($entity);
    foreach ($conditions as $condition) {
      if ($condition instanceof CacheableDependencyInterface) {
        $access->addCacheableDependency($condition);
      }
    }

    return $access;
  }

  /**
   * Get the prepared conditions from the block.
   *
   * @param \Drupal\script_manager\Entity\ScriptInterface $entity
   *   The entity to get conditions for.
   *
   * @throws \Drupal\Component\Plugin\Exception\ContextException
   *
   * @return array
   *   An array of conditions.
   */
  protected function getPreparedConditions(ScriptInterface $entity) {
    $conditions = [];
    foreach ($entity->getVisibilityConditions() as $condition_id => $condition) {
      if ($condition instanceof ContextAwarePluginInterface) {
        $contexts = $this->contextRepository->getRuntimeContexts(array_values($condition->getContextMapping()));
        $this->contextHandler->applyContextMapping($condition, $contexts);
        $conditions[$condition_id] = $condition;
      }
    }
    return $conditions;
  }

}
