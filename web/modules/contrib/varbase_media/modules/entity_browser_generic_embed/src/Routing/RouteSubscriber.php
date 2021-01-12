<?php

namespace Drupal\entity_browser_generic_embed\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\entity_browser_generic_embed\Form\EntityEmbedDialog;
use Symfony\Component\Routing\RouteCollection;

/**
 * Route Subscriber.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $collection
      ->get('entity_embed.dialog')
      ->setDefault('_form', EntityEmbedDialog::class);
  }

}
