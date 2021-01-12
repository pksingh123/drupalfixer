<?php

namespace Drupal\simple_popup_blocks\Controller;

use Drupal\Core\Database\Connection;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Controller\ControllerBase;
use Drupal\simple_popup_blocks\SimplePopupBlocksStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller routines for manage page routes.
 */
class SimplePopupBlocksController extends ControllerBase {

  /**
   * Messenger object.
   *
   * @var Messenger
   */
  protected $messenger;

  /**
   * Connection object.
   *
   * @var Connection
   */
  private $database;

  /**
   * {@inheritdoc}
   */
  public function __construct(Messenger $messenger, Connection $database) {
    $this->messenger = $messenger;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('database')
    );
  }


  /**
   * Manage page controller method to list the data.
   */
  public function manage() {
    $trigger_method = '';
    $header = [
      ['data' => $this->t('S.No')],
      ['data' => $this->t('Popup selector')],
      ['data' => $this->t('Popup sourse')],
      ['data' => $this->t('Layout')],
      ['data' => $this->t('Triggering')],
      ['data' => $this->t('Status')],
      ['data' => $this->t('Edit')],
      ['data' => $this->t('Delete')],
    ];

    $result = SimplePopupBlocksStorage::loadAll($this->database);

    $rows = [];
    $increment = 1;
    foreach ($result as $row) {
      $popup_src = $row->type == 1 ? 'Custom css' : 'Drupal blocks';
      $url = Url::fromRoute('simple_popup_blocks.edit', ['first' => $row->pid]);
      $edit = Link::fromTextAndUrl($this->t('Edit'), $url);
      $edit = $edit->toRenderable();

      $url = Url::fromRoute('simple_popup_blocks.delete', ['first' => $row->pid]);
      $delete = Link::fromTextAndUrl($this->t('Delete'), $url);
      $delete = $delete->toRenderable();

      $layouts = [
        0 => $this
          ->t('Top left'),
        1 => $this
          ->t('Top Right'),
        2 => $this
          ->t('Bottom left'),
        3 => $this
          ->t('Bottom Right'),
        4 => $this
          ->t('Center'),
        5 => $this
          ->t('Top center'),
        6 => $this
          ->t('Top bar'),
        7 => $this
          ->t('Bottom bar'),
        8 => $this
          ->t('Left bar'),
        9 => $this
          ->t('Right bar'),
      ];
      $layout = $layouts[$row->layout];
      $status = $row->status ? 'Active' : 'Inactive';
      switch ($row->trigger_method) {
        case '0':
          $trigger_method = 'Automatic';
          break;

        case '1':
          $trigger_method = 'Manual';
          break;

        case '2':
          $trigger_method = 'Browser/tab close';
          break;
      }
      $rows[] = [
        ['data' => $increment],
        ['data' => $row->identifier],
        ['data' => $popup_src],
        ['data' => $layout],
        ['data' => $trigger_method],
        ['data' => $status],
        ['data' => $edit],
        ['data' => $delete],
      ];
      $increment++;
    }
    $build['table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => 'No popup settings available.',

    ];

    return $build;
  }

  /**
   * Delete page controller.
   */
  public function delete($first) {
    $result = SimplePopupBlocksStorage::delete($first, $this->database);
    if ($result) {
      $this->messenger->addMessage($this->t('Successfully deleted the popup settings.'));
    }
    return $this->redirect('simple_popup_blocks.manage');
  }
}
