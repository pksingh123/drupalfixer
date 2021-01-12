<?php

namespace Drupal\Tests\views_bulk_operations\Functional;

use Drupal\Tests\BrowserTestBase;
use Drush\TestTraits\DrushTestTrait;

/**
 * @coversDefaultClass \Drupal\views_bulk_operations\Commands\ViewsBulkOperationsCommands
 * @group views_bulk_operations
 */
class DrushCommandsTest extends BrowserTestBase {
  use DrushTestTrait;

  const TEST_NODE_COUNT = 15;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = [
    'node',
    'views',
    'views_bulk_operations',
    'views_bulk_operations_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create some nodes for testing.
    $this->drupalCreateContentType(['type' => 'page']);

    $this->testNodes = [];
    $time = $this->container->get('datetime.time')->getRequestTime();
    for ($i = 0; $i < self::TEST_NODE_COUNT; $i++) {
      // Ensure nodes are sorted in the same order they are inserted in the
      // array.
      $time -= $i;
      $this->testNodes[] = $this->drupalCreateNode([
        'type' => 'page',
        'title' => 'Title ' . $i,
        'sticky' => FALSE,
        'created' => $time,
        'changed' => $time,
      ]);
    }

  }

  /**
   * Tests the VBO Drush command.
   */
  public function testDrushCommand() {
    $this->drush('vbo-exec', ['views_bulk_operations_test', 'views_bulk_operations_simple_test_action']);
    $this->assertStringContainsString('Test action (preconfig: , label: Title 0)', $this->getErrorOutput());
    $this->assertStringContainsString('Test action (preconfig: , label: Title 14)', $this->getErrorOutput());
  }

}
