<?php

namespace Drupal\simple_popup_blocks\Form;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Form\FormStateInterface;
use Drupal\simple_popup_blocks\SimplePopupBlocksStorage;

/**
 * Form to add a database entry, with all the interesting fields.
 */
class SimplePopupBlocksEditForm extends SimplePopupBlocksAddForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_popup_blocks_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $first = NULL) {
    // Query for items to display.
    $entry = SimplePopupBlocksStorage::load($first, $this->database);

    // Tell the user if there is nothing to display.
    if (empty($entry)) {
      $form['no_values'] = [
        '#markup' => t('<h3>No results found. Please goto manage popups page.</h3>'),
      ];
      return $form;
    }
    $entry = $entry[0];

    $form = parent::buildForm($form, $form_state);
    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable this as popup.'),
      '#default_value' => $entry->status,
      '#weight' => -99,
    ];
    $visit_counts = unserialize($entry->visit_counts);
    $identifier = $entry->identifier;
    $form['type']['#default_value'] = $entry->type;
    $form['block_list']['#default_value'] = $identifier;
    $form['custom_css']['#default_value'] = $identifier;
    $form['css_selector']['#default_value'] = $entry->css_selector;
    $form['layout']['#default_value'] = $entry->layout;
    $form['visit_counts']['#default_value'] = $visit_counts;
    $form['minimize']['#default_value'] = $entry->minimize;
    $form['close']['#default_value'] = $entry->close;
    $form['escape']['#default_value'] = $entry->escape;
    $form['overlay']['#default_value'] = $entry->overlay;
    $form['trigger_method']['#default_value'] = $entry->trigger_method;
    $form['trigger_selector']['#default_value'] = $entry->trigger_selector;
    $form['delay']['#default_value'] = $entry->delay;
    $form['width']['#default_value'] = $entry->width;
    $form['cookie_expiry']['#default_value'] = $entry->cookie_expiry;

    $block_id_append = '';
    if ($entry->type == 0) {
      $block_id_append = 'block-';
      $identifier = preg_replace('/[_]+/', '-', $identifier);
    }
    $identifier = $block_id_append . $identifier;

    $parent = "#spb-" . $identifier;
    $modal_class = "." . $identifier . "-modal";
    $modal_close_class = "." . $identifier . "-modal-close";
    $modal_minimize_class = "." . $identifier . "-modal-minimize";
    $modal_minimized_class = "." . $identifier . "-modal-minimized";

    $positions = [
      0 => $this
        ->t('spb_top_left'),
      1 => $this
        ->t('spb_top_right'),
      2 => $this
        ->t('spb_bottom_left'),
      3 => $this
        ->t('spb_bottom_right'),
      4 => $this
        ->t('spb_center'),
      5 => $this
        ->t('spb_top_center'),
      6 => $this
        ->t('spb_top_bar'),
      7 => $this
        ->t('spb_bottom_bar'),
      8 => $this
        ->t('spb_left_bar'),
      9 => $this
        ->t('spb_right_bar'),
    ];
    $override_positions = $modal_class . ' .' . $positions[$entry->layout];
    $css_selector = '#';
    if ($entry->type && $entry->css_selector == 0) {
      $css_selector = '.';
    }
    $form['adjustments']['#description'] = $this->t('Use the following css selectors to customize the popup designs.');

    $rows = [
      ['Parent', $parent],
      ['Identifier', $css_selector . $identifier],
      ['Modal class', $modal_class],
      ['Modal close class', $modal_close_class],
      ['Modal minimize class', $modal_minimize_class],
      ['Modal minimized class', $modal_minimized_class],
      ['Override positions', $override_positions],
    ];

    $form['adjustments']['table'] = [
      '#type' => 'table',
      '#rows' => $rows,
      '#empty' => t('No results found'),
    ];
    $documentation = Url::fromUri('https://www.drupal.org/docs/8/modules/simple-popup-blocks');
    $documentation = Link::fromTextAndUrl($this->t('here'), $documentation);
    $documentation = $documentation->toRenderable();
    $documentation = render($documentation);
    $display_none = "<p><h3>Add this css code in your css file (Recommended)</h3><p>";
    $display_none .= "<code>
      " . $css_selector . $identifier . " {<br>
      &nbsp;&nbsp;display: none;<br>
      }</code><br>";
    $display_none .= "<p>While page loading with slow internet connection, content of popup will be visible to users. So you should add this css code in your theme or any css file.</p>";

    $display_none .= "<p>See the documentation " . $documentation . "</p>";

    $form['adjustments']['display_none'] = [
      '#markup' => $display_none,
    ];
    // Set a value by key.
    $form_state->set('simple_popup_blocks_id', $first);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Get a value by key.
    $first = $form_state->get('simple_popup_blocks_id');
    if ($form_state->getValue('type') == 0) {
      $selector = 'block_list';
      $identifier = $form_state->getValue($selector);
    }
    else {
      $selector = 'custom_css';
      $identifier = $form_state->getValue($selector);
    }
    if (!isset($identifier) || empty($identifier)) {
      $form_state->setError($form[$selector], $this->t('You should provide some css selector.'));
    }
    if ($form_state->getValue('type') == 1) {
      // Get the first character using substr.
      $firstCharacter = substr($identifier, 0, 1);
      if (in_array($firstCharacter, ['.', '#'])) {
        $form_state->setError($form[$selector], $this->t('Selector should not start with . or # in %field.', ['%field' => $identifier]));
      }
    }
    $check = SimplePopupBlocksStorage::loadCountByIdentifier($identifier, $this->database, $this->messenger);
    if (!empty($check) && $check[0]->pid != $first) {
      $form_state->setError($form[$selector], $this->t('Already popup created with this identifier %field.', ['%field' => $identifier]));
    }
    $trigger_method = $form_state->getValue('trigger_method');
    if ($trigger_method == 1) {
      $trigger_selector = $form_state->getValue('trigger_selector');
      // Get the first character using substr.
      $firstCharacter = substr($trigger_selector, 0, 1);
      if (!in_array($firstCharacter, ['.', '#'])) {
        $form_state->setError($form['trigger_selector'], $this->t('Selector should start with . or # only in %field.', ['%field' => $trigger_selector]));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get a value by key.
    $first = $form_state->get('simple_popup_blocks_id');
    if ($form_state->getValue('type') == 0) {
      $identifier = $form_state->getValue('block_list');
    }
    else {
      $identifier = $form_state->getValue('custom_css');
    }
    $visit_counts = serialize($form_state->getValue('visit_counts'));
    $delay = $form_state->getValue('delay');
    if (empty($delay) || $delay < 0) {
      $delay = 0;
    }
    $width = $form_state->getValue('width');
    if (empty($width) || $width < 0) {
      $width = 400;
    }
    $cookie_expiry = $form_state->getValue('cookie_expiry');
    if (strlen($cookie_expiry) == 0 || $cookie_expiry < 0) {
      $cookie_expiry = 100;
    }
    // Save the submitted entry.
    $entry = [
      'pid' => $first,
      'identifier' => $identifier,
      'type' => $form_state->getValue('type'),
      'css_selector' => $form_state->getValue('css_selector'),
      'layout' => $form_state->getValue('layout'),
      'visit_counts' => $visit_counts,
      'overlay' => $form_state->getValue('overlay'),
      'trigger_method' => $form_state->getValue('trigger_method'),
      'trigger_selector' => $form_state->getValue('trigger_selector'),
      'escape' => $form_state->getValue('escape'),
      'delay' => $delay,
      'minimize' => $form_state->getValue('minimize'),
      'close' => $form_state->getValue('close'),
      'width' => $width,
      'cookie_expiry' => $cookie_expiry,
      'status' => $form_state->getValue('status'),
    ];
    $return = SimplePopupBlocksStorage::update($entry, $this->database, $this->messenger);
    if ($return) {
      $this->messenger->addMessage($this->t('Popup settings has been updated Successfully.'));
      $url = Url::fromRoute('simple_popup_blocks.manage');
      $form_state->setRedirectUrl($url);
    }
    else {
      $this->messenger->addError($this->t('Error while creating.'), 'error');
    }
  }
}
