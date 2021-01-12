<?php

namespace Drupal\Tests\file_mdm_font\Kernel;

use Drupal\file_mdm\FileMetadataInterface;
use Drupal\Tests\file_mdm\Kernel\FileMetadataManagerTestBase;

/**
 * Tests that the file metadata 'font' plugin works properly.
 *
 * @group File Metadata
 */
class FileMetadataFontTest extends FileMetadataManagerTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'system',
    'file_mdm',
    'file_mdm_font',
    'file_test',
    'image_effects',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installConfig(['file_mdm_font']);
  }

  /**
   * Test 'font' plugin.
   */
  public function testFontPlugin() {
    // The font files that will be tested.
    $font_files = [
      [
        'uri' => drupal_get_path('module', 'image_effects') . '/tests/fonts/LinLibertineTTF_5.3.0_2012_07_02/LinLibertine_Rah.ttf',
        'count_keys' => 15,
        'test_keys' => [
          ['Version', 'Version 5.3.0 ; ttfautohint (v0.9)'],
          ['version', 'Version 5.3.0 ; ttfautohint (v0.9)'],
          ['VeRsIoN', 'Version 5.3.0 ; ttfautohint (v0.9)'],
          ['FontWeight', 400],
        ],
      ],
      [
        'uri' => drupal_get_path('module', 'image_effects') . '/tests/fonts/LinLibertineTTF_5.3.0_2012_07_02/LinBiolinum_Kah.ttf',
        'count_keys' => 15,
        'test_keys' => [
          ['FullName', 'Linux Biolinum Keyboard'],
          ['fullname', 'Linux Biolinum Keyboard'],
          ['fUlLnAmE', 'Linux Biolinum Keyboard'],
        ],
      ],
    ];

    $fmdm = $this->container->get('file_metadata_manager');

    // Walk through test files.
    foreach ($font_files as $font_file) {
      $file_metadata = $fmdm->uri($font_file['uri']);
      if (!$file_metadata) {
        $this->fail("File not found: {$font_file['uri']}");
        continue;
      }
      $this->assertEquals($font_file['count_keys'], $this->countMetadataKeys($file_metadata, 'font'));
      $this->assertSame(FileMetadataInterface::LOADED_FROM_FILE, $file_metadata->isMetadataLoaded('font'));
      foreach ($font_file['test_keys'] as $test) {
        $this->assertEquals($test[1], $file_metadata->getMetadata('font', $test[0]));
      }
    }
  }

  /**
   * Test 'font' plugin supported keys.
   */
  public function testSupportedKeys() {
    $expected_keys = [
      'FontType',
      'FontWeight',
      'Copyright',
      'FontName',
      'FontSubfamily',
      'UniqueID',
      'FullName',
      'Version',
      'PostScriptName',
      'Trademark',
      'Manufacturer',
      'Designer',
      'Description',
      'FontVendorURL',
      'FontDesignerURL',
      'LicenseDescription',
      'LicenseURL',
      'PreferredFamily',
      'PreferredSubfamily',
      'CompatibleFullName',
      'SampleText',
    ];

    $fmdm = $this->container->get('file_metadata_manager');
    $file_md = $fmdm->uri(drupal_get_path('module', 'image_effects') . '/tests/fonts/LinLibertineTTF_5.3.0_2012_07_02/LinLibertine_Rah.ttf');
    $this->assertEquals($expected_keys, $file_md->getSupportedKeys('font'));
  }

}
