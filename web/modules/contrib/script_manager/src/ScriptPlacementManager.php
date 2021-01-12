<?php

namespace Drupal\script_manager;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Manage script placements.
 */
class ScriptPlacementManager implements ContainerInjectionInterface {

  /**
   * The script storage controller.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $scriptStorage;

  /**
   * A flag for if the current route is an admin route.
   *
   * @var bool
   */
  protected $isAdminRoute;

  /**
   * The module handler to invoke hooks on.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * ScriptPlacementManager constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $scriptStorage
   *   The script entity storage.
   * @param bool $isAdminRoute
   *   Whether the current route is considered an admin route.
   */
  public function __construct(EntityStorageInterface $scriptStorage, $isAdminRoute, ModuleHandlerInterface $moduleHandler) {
    $this->scriptStorage = $scriptStorage;
    $this->isAdminRoute = $isAdminRoute;
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('script'),
      $container->get('router.admin_context')->isAdminRoute(),
      $container->get('module_handler')
    );
  }

  /**
   * Get the rendered scripts for a given position.
   */
  public function getRenderedScriptsForPosition($position) {

    if ($this->isAdminRoute) {
      return [];
    }

    $scripts = $this->scriptStorage->loadByProperties(['position' => $position]);
    $rendered_scripts = [
      '#cache' => [
        'tags' => ['config:script_list'],
      ],
    ];

    foreach ($scripts as $script) {

      $access = $script->access('view', NULL, TRUE);
      $rendered = [
        '#markup' => new FormattableMarkup($script->getSnippet(), []),
        '#access' => $access->isAllowed(),
      ];

      CacheableMetadata::createFromObject($access)
        ->addCacheableDependency($script)
        ->applyTo($rendered);

      $rendered_scripts[] = $rendered;
    }

    $this->moduleHandler->alter('script_manager_scripts', $rendered_scripts);
    return $rendered_scripts;
  }

}
