<?php

namespace Drupal\altersiteinfo\Routing;
 
// Classes referenced in this class
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;
 
/**
 * Listens to the dynamic route events.
 */
class AlterSiteRouteSubscriber extends RouteSubscriberBase {
  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Change form for the system.site_information_settings route
    // to Drupal\altersiteinfo\Form\AlterSiteInformationForm
    // First, we need to act only on the system.site_information_settings route.
    if ($route = $collection->get('system.site_information_settings')) {
      // Next, we need to set the value for _form to the form we have created.
      $route->setDefault('_form', 'Drupal\altersiteinfo\Form\AlterSiteInformationForm');
    }
  }
}