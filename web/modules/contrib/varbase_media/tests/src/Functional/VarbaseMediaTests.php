<?php

namespace Drupal\Tests\varbase_media\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Varbase Media tests.
 *
 * @group varbase_media
 */
class VarbaseMediaTests extends BrowserTestBase {

  use StringTranslationTrait;

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = [
    'metatag',
    'metatag_views',
    'better_exposed_filters',
    'varbase_media',
    'varbase_media_instagram',
    'varbase_media_twitter',
  ];

  /**
   * Specify the theme to be used in testing.
   *
   * @var string
   */
  protected $defaultTheme = 'bartik';

  /**
   * The profile to install as a basis for testing.
   *
   * @var string
   */
  protected $profile = 'standard';

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalLogin($this->rootUser);
  }

  /**
   * Check Varbase Media Types.
   */
  public function testCheckVarbaseMediaTypesPage() {
    $assert_session = $this->assertSession();

    $this->drupalGet('/admin/structure/media');

    $assert_session->pageTextContains($this->t('Image'));
    $assert_session->pageTextContains($this->t('Remote video'));
    $assert_session->pageTextContains($this->t('Video'));
    $assert_session->pageTextContains($this->t('Gallery'));
    $assert_session->pageTextContains($this->t('Instagram'));
    $assert_session->pageTextContains($this->t('Tweet'));
    $assert_session->pageTextContains($this->t('Document'));

  }

  /**
   * Check Varbase Media settings page.
   */
  public function testCheckVarbaseMediaSettings() {
    $assert_session = $this->assertSession();

    $this->drupalGet('/admin/people/permissions#module-varbase_media');
    $assert_session->pageTextContains($this->t('Administer Varbase Media settings'));
  }

  /**
   * Check permissions to Administer Varbase Media settings.
   */
  public function testCheckVarbaseMediaSettingsPermissions() {
    $assert_session = $this->assertSession();

    $this->drupalGet('/admin/people/permissions');
    $assert_session->pageTextContains($this->t('Administer Varbase Media settings'));
  }

}
