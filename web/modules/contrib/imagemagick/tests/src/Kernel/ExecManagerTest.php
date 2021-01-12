<?php

namespace Drupal\Tests\imagemagick\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests for ImagemagickExecManager.
 *
 * @group Imagemagick
 */
class ExecManagerTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['imagemagick', 'file_mdm', 'sophron'];

  /**
   * Test missing command on ExecManager.
   */
  public function testExecManagerCommandNotFound() {
    $exec_manager = \Drupal::service('imagemagick.exec_manager');
    $output = '';
    $error = '';
    $expected = substr(PHP_OS, 0, 3) !== 'WIN' ? 127 : 1;
    $ret = $exec_manager->runOsShell('pinkpanther', '-inspector Clouseau', 'blake', $output, $error);
    $this->assertEquals($expected, $ret, $error);
  }

  /**
   * Test timeout on ExecManager.
   */
  public function testExecManagerTimeout() {
    $exec_manager = \Drupal::service('imagemagick.exec_manager');
    $output = '';
    $error = '';
    $expected = substr(PHP_OS, 0, 3) !== 'WIN' ? 143 : 1;
    // Set a short timeout (1 sec.) and run a process that is expected to last
    // longer (10 secs.). Should return a 'terminate' exit code.
    $exec_manager->setTimeout(1);
    $ret = $exec_manager->runOsShell('sleep', '10', 'sleep', $output, $error);
    $this->assertEquals($expected, $ret, $error);
  }

}
