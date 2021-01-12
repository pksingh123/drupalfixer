<?php


namespace Drupal\views_infinite_scroll\Plugin\views\pager;

use Drupal\views\Plugin\views\pager\SqlBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Views pager plugin to handle infinite scrolling.
 *
 * @ViewsPager(
 *  id = "infinite_scroll",
 *  title = @Translation("Infinite Scroll"),
 *  short_title = @Translation("Infinite Scroll"),
 *  help = @Translation("A views plugin which provides infinte scroll."),
 *  theme = "views_infinite_scroll_pager"
 * )
 */
class InfiniteScroll extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function render($input) {
    // Replace tokens in the button text.
    $text = $this->options['views_infinite_scroll']['button_text'];
    if (!empty($text) && strpos($text, '@') !== FALSE) {
      $replacements = [
        '@next_page_count' => $this->getNumberItemsLeft(),
        '@total' => (int) $this->getTotalItems(),
      ];
      $this->options['views_infinite_scroll']['button_text'] = strtr($text, $replacements);
    }

    return [
      '#theme' => $this->themeFunctions(),
      '#options' => $this->options['views_infinite_scroll'],
      '#attached' => [
        'library' => ['views_infinite_scroll/views-infinite-scroll'],
      ],
      '#element' => $this->options['id'],
      '#parameters' => $input,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defineOptions() {
    $options = parent::defineOptions();
    $options['views_infinite_scroll'] = [
      'contains' => [
        'button_text' => [
          'default' => $this->t('Load More'),
        ],
        'automatically_load_content' => [
          'default' => FALSE,
        ],
      ],
    ];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function summaryTitle() {
    $action = $this->options['views_infinite_scroll']['automatically_load_content'] ? $this->t('Automatic infinite scroll') : $this->t('Click to load');
    return $this->formatPlural($this->options['items_per_page'], '@action, @count item', '@action, @count items', ['@action' => $action, '@count' => $this->options['items_per_page']]);
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['tags']['#access'] = FALSE;
    $options = $this->options['views_infinite_scroll'];

    $form['views_infinite_scroll'] = [
      '#title' => $this->t('Infinite Scroll Options'),
      '#description' => $this->t('Note: The infinite scroll option overrides and requires the <em>Use AJAX</em> setting for this views display.'),
      '#type' => 'details',
      '#open' => TRUE,
      '#tree' => TRUE,
      '#input' => TRUE,
      '#weight' => -100,
      'button_text' => [
        '#type' => 'textfield',
        '#title' => $this->t('Button Text'),
        '#default_value' => $options['button_text'],
        '#description' => [
          '#theme' => 'item_list',
          '#items' => [
            '@next_page_count -- the next page record count',
            '@total -- the total amount of results returned from the view',
          ],
          '#prefix' => $this->t('The following tokens are supported:'),
        ],
      ],
      'automatically_load_content' => [
        '#type' => 'checkbox',
        '#title' => $this->t('Automatically Load Content'),
        '#description' => $this->t('Automatically load subsequent pages as the user scrolls.'),
        '#default_value' => $options['automatically_load_content'],
      ],
    ];
  }

  /**
   * Returns the number of items in the next page.
   *
   * @return int
   *   The number of items in the next page.
   */
  protected function getNumberItemsLeft() {
    $items_per_page = (int) $this->view->getItemsPerPage();
    $total = (int) $this->getTotalItems();
    $current_page = (int) $this->getCurrentPage() + 1;

    // Default to the pager amount.
    $next_page_count = $items_per_page;
    // Calculate the remaining items if we are at the 2nd to last page.
    if ($current_page >= ceil($total / $items_per_page) - 1) {
      $next_page_count = $total - ($current_page * $items_per_page);
      return $next_page_count;
    }
    return $next_page_count;
  }

}
