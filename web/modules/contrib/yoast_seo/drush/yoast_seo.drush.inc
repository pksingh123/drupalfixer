<?php

/**
 * @file
 * Drush integration for the yoast_seo module.
 */

/**
 * Implements hook_drush_command().
 */
function yoast_seo_drush_command() {
  $items = array();

  // Command yoastseo-prepare-uninstall
  // prepares the module to be uninstalled.
  $items['yoastseo-prepare-uninstall'] = array(
    'description' => "Prepare uninstalling Real-time SEO. Remove fields and data.",
    'aliases' => array('ypu'),
  );

  return $items;
}

/**
 * Prepare uninstall for yoast seo.
 *
 * This enables to delete the fields from drupal so uninstalling the module
 * doesn't fail.
 * It does 3 things :
 * - Remove Yoast SEO fields from the content view
 * - Detach Yoast SEO from all yoastseo enabled content types
 * - Purge the fields list from all temporary deleted fields.
 *
 * @see https://www.drupal.org/node/2418659
 */
function drush_yoast_seo_yoastseo_prepare_uninstall() {
  $supported_entities = [
    'node' => 'Node',
    'taxonomy_term' => 'Taxonomy term',
  ];
  $yoast_seo_manager = \Drupal::service('yoast_seo.manager');

  // 1) Remove field from content view.
  $yoast_seo_manager->detachFieldHandlerFromContentView();

  // 2) Detach Yoast SEO from all the content types it is attached to.
  foreach ($supported_entities as $entity_type_id => $entity_type_label) {
    $enabled_bundles = $yoast_seo_manager->getEnabledBundles($entity_type_id);
    if (!empty($enabled_bundles)) {
      foreach ($enabled_bundles as $bundle_id) {
        echo "detach yoast_seo fields from $entity_type_id $bundle_id\n";
        $yoast_seo_manager->detachYoastSeoFields($entity_type_id, $bundle_id);
      }
    }
  }

  // 3) Purge field data.
  do {
    field_purge_batch(1000);
    $fields = \Drupal::entityTypeManager()
      ->getStorage('field_config')
      ->loadByProperties([
        'deleted' => TRUE,
        'include_deleted' => TRUE,
      ]);
  } while ($fields);

}
