<?php

namespace Drupal\altersiteinfo\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\node\Entity\Node;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 * id = "page_node_rest_resource",
 * label = @Translation("Page node rest resource"),
 * uri_paths = {
 * "canonical" = "/page_json/{siteapikey}/{nid}",
 * 
 * }
 * )
 */
class PageNodeRestResource extends ResourceBase {

  public function get($siteapikey = NULL, $nid = NULL) {
    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.

    $site_config = \Drupal::config('system.site');
    $system_siteapikey = $site_config->get('siteapikey');

    $respose_array = array();

    if ($system_siteapikey != $siteapikey || empty($siteapikey)) {
      $respose_array = array('message' => 'access denied');
    } else {
      if ($nid) {
        $node = \Drupal\node\Entity\Node::load($nid);
        if ($node->getType() === 'page') {
          $respose_array = $node;
        } else {
          $respose_array = array('message' => 'Only page type content allowed');
        }
      }
    }
    $response = new ResourceResponse($respose_array);
    // In order to generate fresh result every time (without clearing 
    // the cache), you need to invalidate the cache.
    $response->addCacheableDependency($respose_array);
    return $response;
  }

}
