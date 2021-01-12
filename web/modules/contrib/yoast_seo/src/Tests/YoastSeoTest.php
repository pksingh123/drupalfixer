<?php

namespace Drupal\yoast_seo\Tests;

use Drupal\Tests\BrowserTestBase;

/**
 * Ensures that the Yoast Seo works correctly.
 *
 * @group YoastSeo
 */
class YoastSeoTest extends BrowserTestBase {

  /**
   * Admin user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'field_ui',
    'metatag',
    'yoast_seo',
    'entity_test',
    'node',
  ];

  /**
   * Permissions to grant admin user.
   *
   * @var array
   */
  protected $permissions = [
    'access administration pages',
    'administer content types',
    'administer nodes',
    'administer meta tags',
    'administer yoast seo',
    'view test entity',
    'access content',
  ];

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * Sets the test up.
   */
  protected function setUp() {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser($this->permissions);
    $this->entityManager = \Drupal::service('entity_type.manager');
  }

  /**
   * Enable Yoast SEO for a given bundle.
   */
  protected function enableYoastSeo($entity_type, $bundle) {
    // Configure yoast seo for the given bundle.
    $this->drupalGet('admin/config/yoast_seo');
    $edit = [$entity_type . '[' . $bundle . ']' => $bundle];
    json_decode($this->drupalPostForm(NULL, $edit, t('Save')));
    $this->assertSession()->checkboxChecked('edit-node-page');
  }

  /**
   * Disable Yoast SEO for a given bundle.
   */
  protected function disableYoastSeo($entity_type, $bundle) {
    // Configure yoast seo for the given bundle.
    $this->drupalGet('admin/config/yoast_seo');
    $edit = [$entity_type . '[' . $bundle . ']' => FALSE];
    json_decode($this->drupalPostForm(NULL, $edit, t('Save')));
    $this->assertSession()->checkboxNotChecked('edit-node-page');
  }

  /**
   * Only available when it has been previously enabled on the content type.
   *
   * Given    I am logged in as admin
   * When     I am adding a content on a content type which doesn't have a Meta
   * Tag field
   * Then     Then I should not see the Yoast SEO section active
   * When     I am adding a content on a content type which have a Meta Tag
   * field.
   */
  public function testYoastSeoEnabledDisabled() {
    // Given I am logged in as admin.
    $this->drupalLogin($this->adminUser);
    // Create a page node type.
    $this->entityManager->getStorage('node_type')->create([
      'type' => 'page',
      'name' => 'page',
    ])->save();

    // When I am adding an Entity Test content.
    $this->drupalGet('node/add/page');
    // Then I should not see the Yoast SEO section active.
    $this->assertSession()->pageTextNotContains('Yoast SEO for drupal');

    // When I enable Yoast SEO for the page bundle.
    $this->enableYoastSeo('node', 'page');
    // And I am adding an Entity Test content.
    $this->drupalGet('node/add/page');
    // Then I should see the Yoast SEO section active.
    $this->assertSession()->pageTextContains('Real-time SEO for drupal');

    // When I disable Yoast SEO for the page bundle.
    $this->disableYoastSeo('node', 'page');
    // And I am adding an Entity Test content.
    $this->drupalGet('node/add/page');
    // Then I should not see the Yoast SEO section active.
    $this->assertSession()->pageTextNotContains('Real-time SEO for drupal');
  }

}
