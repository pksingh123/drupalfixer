<?php

namespace Drupal\views_bulk_operations\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\views_bulk_operations\Form\ViewsBulkOperationsFormTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\views_bulk_operations\Service\ViewsBulkOperationsActionProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Ajax\AjaxResponse;

/**
 * Defines VBO controller class.
 */
class ViewsBulkOperationsController extends ControllerBase implements ContainerInjectionInterface {

  use ViewsBulkOperationsFormTrait;

  /**
   * The tempstore service.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * Views Bulk Operations action processor.
   *
   * @var \Drupal\views_bulk_operations\Service\ViewsBulkOperationsActionProcessorInterface
   */
  protected $actionProcessor;

  /**
   * Constructs a new controller object.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $tempStoreFactory
   *   Private temporary storage factory.
   * @param \Drupal\views_bulk_operations\Service\ViewsBulkOperationsActionProcessorInterface $actionProcessor
   *   Views Bulk Operations action processor.
   */
  public function __construct(
    PrivateTempStoreFactory $tempStoreFactory,
    ViewsBulkOperationsActionProcessorInterface $actionProcessor
  ) {
    $this->tempStoreFactory = $tempStoreFactory;
    $this->actionProcessor = $actionProcessor;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('views_bulk_operations.processor')
    );
  }

  /**
   * The actual page callback.
   *
   * @param string $view_id
   *   The current view ID.
   * @param string $display_id
   *   The display ID of the current view.
   */
  public function execute($view_id, $display_id) {
    $view_data = $this->getTempstoreData($view_id, $display_id);
    if (empty($view_data)) {
      throw new NotFoundHttpException();
    }
    $this->deleteTempstoreData();

    $this->actionProcessor->executeProcessing($view_data);
    if ($view_data['batch']) {
      return batch_process($view_data['redirect_url']);
    }
    else {
      return new RedirectResponse($view_data['redirect_url']->setAbsolute()->toString());
    }
  }

  /**
   * AJAX callback to update selection (multipage).
   *
   * @param string $view_id
   *   The current view ID.
   * @param string $display_id
   *   The display ID of the current view.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   */
  public function updateSelection($view_id, $display_id, Request $request) {
    $view_data = $this->getTempstoreData($view_id, $display_id);
    if (empty($view_data)) {
      throw new NotFoundHttpException();
    }

    $list = $request->request->get('list');

    $op = $request->request->get('op', 'check');
    // Reverse operation when in exclude mode.
    if (!empty($view_data['exclude_mode'])) {
      if ($op === 'add') {
        $op = 'remove';
      }
      elseif ($op === 'remove') {
        $op = 'add';
      }
    }

    switch ($op) {
      case 'add':
        foreach ($list as $bulkFormKey) {
          if (!isset($view_data['list'][$bulkFormKey])) {
            $view_data['list'][$bulkFormKey] = $this->getListItem($bulkFormKey);
          }
        }
        break;

      case 'remove':
        foreach ($list as $bulkFormKey) {
          if (isset($view_data['list'][$bulkFormKey])) {
            unset($view_data['list'][$bulkFormKey]);
          }
        }
        break;

      case 'method_include':
        unset($view_data['exclude_mode']);
        $view_data['list'] = [];
        break;

      case 'method_exclude':
        $view_data['exclude_mode'] = TRUE;
        $view_data['list'] = [];
        break;
    }

    $this->setTempstoreData($view_data);

    $count = empty($view_data['exclude_mode']) ? count($view_data['list']) : $view_data['total_results'] - count($view_data['list']);

    $response = new AjaxResponse();
    $response->setData(['count' => $count]);
    return $response;
  }

}
