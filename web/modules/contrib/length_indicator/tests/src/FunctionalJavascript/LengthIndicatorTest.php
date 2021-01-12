<?php

namespace Drupal\Tests\length_indicator\FunctionalJavascript;

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\entity_test\Entity\EntityTest;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Tests the UI for entity displays.
 *
 * @group length_indicator
 */
class LengthIndicatorTest extends WebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['length_indicator', 'field_ui', 'entity_test'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create a field of each supported type.
    $this->createField('string', 'string_textfield');
    $this->createField('string_long', 'string_textarea');

    $this->drupalLogin($this->drupalCreateUser([
      'access administration pages',
      'view test entity',
      'administer entity_test content',
      'administer entity_test fields',
      'administer entity_test display',
      'administer entity_test form display',
      'view the administration theme',
    ]));
  }

  /**
   * Creates a field on the entity_test entity type.
   *
   * @param string $field_type
   *   The field type.
   * @param string $widget_type
   *   The widget type.
   */
  protected function createField($field_type, $widget_type) {
    // Use the field type as a name to make things simple.
    $field_name = $field_type;

    $field_storage = FieldStorageConfig::create([
      'field_name' => $field_name,
      'entity_type' => 'entity_test',
      'type' => $field_type,
    ]);
    $field_storage->save();

    FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'entity_test',
      'label' => $this->randomMachineName() . '_label',
    ])->save();

    EntityFormDisplay::load('entity_test.entity_test.default')
      ->setComponent($field_name, [
        'type' => $widget_type,
        'settings' => [
          'placeholder' => 'A placeholder on ' . $widget_type,
        ],
      ])
      ->save();
  }

  /**
   * Tests the length indicator.
   */
  public function testLengthIndicator() {
    // Create a test entity.
    $entity = EntityTest::create([
      'name' => 'The name for this entity',
      'string' => [
        ['value' => 'A value in a string field'],
      ],
      'string_long' => [
        ['value' => 'A value in a string_long field'],
      ],
    ]);
    $entity->save();
    $form_display = EntityFormDisplay::load('entity_test.entity_test.default');

    /** @var \Drupal\Core\Field\WidgetInterface $string */
    $values = $form_display->getComponent('string');
    $values['third_party_settings']['length_indicator'] = [
      'indicator' => TRUE,
      'indicator_opt' => ['optimin' => 15, 'optimax' => 30, 'tolerance' => 6],
    ];
    $form_display->setComponent('string', $values);
    $values = $form_display->getComponent('string_long');
    $values['third_party_settings']['length_indicator'] = [
      'indicator' => TRUE,
      'indicator_opt' => ['optimin' => 100, 'optimax' => 300, 'tolerance' => 40],
    ];
    $form_display->setComponent('string_long', $values);
    $form_display->save();

    $this->drupalGet('entity_test/manage/1/edit');

    // Test the string field.
    $this->assertActiveElement('string', 'good');
    $this->assertActiveElement('string', 'bad', '');
    $this->assertActiveElement('string', 'bad', $this->randomString(8));
    $this->assertActiveElement('string', 'ok', $this->randomString(9));
    $this->assertActiveElement('string', 'ok', $this->randomString(14));
    $this->assertActiveElement('string', 'good', $this->randomString(15));
    $this->assertActiveElement('string', 'good', $this->randomString(30));
    $this->assertActiveElement('string', 'ok', $this->randomString(31));
    $this->assertActiveElement('string', 'ok', $this->randomString(36));
    $this->assertActiveElement('string', 'bad', $this->randomString(37));

    // Test the string_long field.
    $this->assertActiveElement('string_long', 'bad');
    $this->assertActiveElement('string', 'bad', '');
    $this->assertActiveElement('string_long', 'bad', $this->randomString(59));
    $this->assertActiveElement('string_long', 'ok', $this->randomString(60));
    $this->assertActiveElement('string_long', 'ok', $this->randomString(99));
    $this->assertActiveElement('string_long', 'good', $this->randomString(100));
    $this->assertActiveElement('string_long', 'good', $this->randomString(300));
    $this->assertActiveElement('string_long', 'ok', $this->randomString(301));
    $this->assertActiveElement('string_long', 'ok', $this->randomString(340));
    $this->assertActiveElement('string_long', 'bad', $this->randomString(341));
  }

  /**
   * Tests the field length indicator for a specific field.
   *
   * @param string $field_name
   *   The field to test.
   * @param string $class_modifier
   *   The class modifier that you expect the active indicator to have.
   * @param string $value
   *   (optional) Set the field to this value before testing the indicator.
   */
  protected function assertActiveElement($field_name, $class_modifier, $value = NULL) {
    if (!is_null($value)) {
      $this->getSession()->getPage()->fillField($field_name . '[0][value]', $value);
    }
    $active_elements = $this->xpath('//*[@id="edit-' . str_replace('_', '-', $field_name) . '-wrapper"]/div[2]/span[contains(@class, "is-active")]');
    $this->assertCount(1, $active_elements);
    $this->assertContains("length-indicator__indicator--$class_modifier", $active_elements[0]->getAttribute('class'), "$field_name field's active indicator has class modifier '$class_modifier'");
  }

  /**
   * Tests the length indicator widget settings form.
   */
  public function testLengthIndicatorSettings() {
    $this->drupalGet('entity_test/structure/entity_test/form-display');
    $this->xpath('//input[@data-drupal-selector="edit-fields-string-settings-edit"]')[0]->click();
    $this->assertSession()->assertWaitOnAjaxRequest();

    $html_output = '<hr />Ending URL: ' . $this->getSession()->getCurrentUrl();
    $html_output .= '<hr />' . $this->getSession()->getPage()->getContent();
    $html_output .= $this->getHtmlOutputHeaders();
    $this->htmlOutput($html_output);

    // Have to click the field for #states to work.
    $this->getSession()->getPage()->checkField('fields[string][settings_edit_form][third_party_settings][length_indicator][indicator]');

    // Default values.
    $this->assertSession()->fieldValueEquals('fields[string][settings_edit_form][third_party_settings][length_indicator][indicator_opt][optimin]', '10');
    $this->assertSession()->fieldValueEquals('fields[string][settings_edit_form][third_party_settings][length_indicator][indicator_opt][optimax]', '15');
    $this->assertSession()->fieldValueEquals('fields[string][settings_edit_form][third_party_settings][length_indicator][indicator_opt][tolerance]', '5');

    // Form error when min is greater than max and tolerance is greater than
    // min.
    $this->getSession()->getPage()->fillField('fields[string][settings_edit_form][third_party_settings][length_indicator][indicator_opt][optimin]', '20');
    $this->getSession()->getPage()->fillField('fields[string][settings_edit_form][third_party_settings][length_indicator][indicator_opt][tolerance]', '21');
    $this->xpath('//input[@data-drupal-selector="edit-fields-string-settings-edit-form-actions-save-settings"]')[0]->click();
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->pageTextContains('Optimum maximum has to be greater than the optimum minimum');
    $this->assertSession()->pageTextContains('Tolerance has to be smaller than the optimum minimum');

    // Fill out the form with some non-default values.
    $this->getSession()->getPage()->fillField('fields[string][settings_edit_form][third_party_settings][length_indicator][indicator_opt][optimin]', '15');
    $this->getSession()->getPage()->fillField('fields[string][settings_edit_form][third_party_settings][length_indicator][indicator_opt][optimax]', '30');
    $this->getSession()->getPage()->fillField('fields[string][settings_edit_form][third_party_settings][length_indicator][indicator_opt][tolerance]', '6');
    $this->xpath('//input[@data-drupal-selector="edit-fields-string-settings-edit-form-actions-save-settings"]')[0]->click();
    $this->assertSession()->assertWaitOnAjaxRequest();
    // This saves the form display which will validate our configuration schema.
    $this->xpath('//input[@data-drupal-selector="edit-submit"]')[0]->click();

    // Check the values.
    $form_display = EntityFormDisplay::load('entity_test.entity_test.default');
    $expected = [
      'indicator' => TRUE,
      'indicator_opt' => ['optimin' => 15, 'optimax' => 30, 'tolerance' => 6],
    ];
    $this->assertEquals($expected, $form_display->getRenderer('string')->getThirdPartySettings('length_indicator'));
  }

}
