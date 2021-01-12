<?php

namespace Drupal\Tests\script_manager\Functional;

use Behat\Mink\Exception\ExpectationException;
use Drupal\script_manager\Entity\Script;
use Drupal\script_manager\Entity\ScriptInterface;
use Drupal\Tests\BrowserTestBase;

/**
 * Test the script manager module.
 *
 * @group script_manager
 */
class ScriptManagerTest extends BrowserTestBase {

  /**
   * Modules to test.
   *
   * @var array
   */
  public static $modules = [
    'script_manager',
  ];

  /**
   * An example script.
   *
   * @var \Drupal\script_manager\Entity\Script
   */
  protected $exampleScript;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->exampleScript = Script::create([
      'id' => 'foo',
      'label' => 'Foo',
      'snippet' => $this->randomMachineName(),
      'position' => ScriptInterface::POSITION_TOP,
    ]);
    $this->exampleScript->save();
  }

  /**
   * Test the different page positions.
   */
  public function testScriptManagerPositions() {
    $this->exampleScript->set('position', ScriptInterface::POSITION_TOP)->save();
    $this->drupalGet('<front>');
    $this->assertOrderInPage([$this->exampleScript->getSnippet(), '</h1>']);

    $this->exampleScript->set('position', ScriptInterface::POSITION_BOTTOM)->save();
    $this->drupalGet('<front>');
    $this->assertOrderInPage(['</h1>', $this->exampleScript->getSnippet()]);

    $this->exampleScript->set('position', ScriptInterface::POSITION_HIDDEN)->save();
    $this->drupalGet('<front>');
    $this->assertScriptNotVisible();
  }

  /**
   * Test the visibility conditions.
   */
  public function testScriptManagerConditions() {
    $this->exampleScript->set('visibility', [
      'request_path' => [
        'id' => 'request_path',
        'pages' => '/user/register',
        'negate' => FALSE,
        'context_mapping' => [],
      ]
    ])->save();

    $this->drupalGet('<front>');
    $this->assertScriptNotVisible();

    $this->drupalGet('user/register');
    $this->assertScriptVisibile();
  }

  /**
   * Assert the script appears on the page.
   */
  protected function assertScriptVisibile() {
    $this->assertSession()
      ->pageTextContains($this->exampleScript->getSnippet());
  }

  /**
   * Assert the script doesn't appears on the page.
   */
  protected function assertScriptNotVisible() {
    $this->assertSession()
      ->pageTextNotContains($this->exampleScript->getSnippet());
  }

  /**
   * Asserts that several pieces of markup are in a given order in the page.
   *
   * @param string[] $items
   *   An ordered list of strings.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   *   When any of the given string is not found.
   */
  public function assertOrderInPage(array $items) {
    $text = $this->getSession()->getPage()->getHtml();
    $strings = [];
    foreach ($items as $item) {
      if (($pos = strpos($text, $item)) === FALSE) {
        throw new ExpectationException("Cannot find '$item' in the page", $this->getSession()->getDriver());
      }
      $strings[$pos] = $item;
    }
    ksort($strings);
    $ordered = implode(', ', array_map(function ($item) {
      return "'$item'";
    }, $items));
    if ($items !== array_values($strings)) {
      throw new ExpectationException("Strings were not correctly ordered as: $ordered.", $this->getSession()->getDriver());
    }
  }

}
