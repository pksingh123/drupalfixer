<?php

namespace Drupal\yoast_seo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * YoastSeoController.
 */
class YoastSeoController extends ControllerBase {

  /**
   * Returns a set of tokens' values.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request of the page.
   *   * data The context to use to retrieve the tokens value,
   *     see Drupal\Core\Utility\token::replace()
   *   * tokens An array of tokens to get the values for.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The JSON response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   In case of AccessDeniedException.
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   *   In case of NotFoundHttpException.
   */
  public function tokens(Request $request) {
    /** @var MetatagToken $metatag_token */
    $metatag_token = \Drupal::service('metatag.token');
    $token_values = array();
    $tokens       = $request->request->get('tokens');
    $data         = $request->request->get('data');

    if (is_null($data)) {
      $data = array();
    }

    // Retrieve the tokens values.
    // Use the metatag token service, which use either core or token module
    // regarding if this one is installed.
    foreach ($tokens as $token) {
      $token_values[$token] = $metatag_token->replace($token, $data);
    }

    return new JsonResponse($token_values);
  }

  /**
   * Settings page.
   *
   * @return array
   *   The configuration form.
   */
  public function settings() {
    $form = [];

    $xmlsitemap_enabled = \Drupal::moduleHandler()->moduleExists('xmlsitemap');
    $simple_sitemap_enabled = \Drupal::moduleHandler()->moduleExists('simple_sitemap');

    // Check if a sitemap module is installed and enabled.
    if ($xmlsitemap_enabled && $simple_sitemap_enabled) {
      // Discourage users from enabling both sitemap modules as they
      // might interfere.
      $xmlsitemap_description
        = $this->t('It looks like you have both the XML Sitemap and Simple XML Sitemap module enabled. Please uninstall one of them as they could interfere with each other.');
    }
    elseif ($xmlsitemap_enabled) {
      // Inform the user about altering the XML Sitemap configuration on the
      // module configuration page if he has access to do so.
      if (\Drupal::currentUser()->hasPermission('administer xmlsitemap')) {
        $xmlsitemap_description = $this->t(
          'You can configure the XML Sitemap settings at the <a href="@url">configuration page</a>',
          [
            '@url' => Url::fromRoute('xmlsitemap.admin_search')->toString(),
          ]
        );
      }
      else {
        $xmlsitemap_description
          = $this->t('You do not have the permission to administer the XML Sitemap.');
      }
    }
    elseif (\Drupal::moduleHandler()->moduleExists('simple_sitemap')) {
      // Inform the user about altering the XML Sitemap configuration on the
      // module configuration page if he has access to do so.
      if (\Drupal::currentUser()->hasPermission('administer simple_sitemap')) {
        $xmlsitemap_description = $this->t(
          'You can configure the Simple XML Sitemap settings at the <a href="@url">configuration page</a>.',
          [
            '@url' => Url::fromRoute('simple_sitemap.settings')->toString(),
          ]
        );
      }
      else {
        $xmlsitemap_description
          = $this->t('You do not have the permission to administer the Simple XML Sitemap.');
      }
    }
    else {
      // XML Sitemap is not enabled, inform the user he should think about
      // installing and enabling it.
      $xmlsitemap_description = $this->t(
        'You currently do not have a sitemap module enabled. We strongly recommend you to install a sitemap module. You can download the <a href="@project1-url">@project1-name</a> or <a href="@project2-url">@project2-name</a> module to use as sitemap generator.',
        [
          '@project1-url' => 'https://www.drupal.org/project/simple_sitemap',
          '@project1-name' => 'Simple Sitemap',
          '@project2-url' => 'https://www.drupal.org/project/xmlsitemap',
          '@project2-name' => 'XML Sitemap',
        ]
       );
    }

    $form['xmlsitemap'] = [
      '#type' => 'details',
      '#title' => $this->t('Sitemap'),
      '#markup' => $xmlsitemap_description,
      '#open' => TRUE,
    ];

    // Inform the user about altering the Metatag configuration on the module
    // configuration page if he has access to do so.
    // We do not check if the module is enabled since it is our dependency.
    if (\Drupal::currentUser()->hasPermission('administer meta tags')) {
      $metatag_description = $this->t(
        'You can configure and override the Metatag title & description default settings at the <a href="@url">Metatag configuration page</a>.',
        [
          '@url' => Url::fromRoute('entity.metatag_defaults.collection')->toString(),
        ]
      );
    }
    else {
      $metatag_description
        = $this->t('You currently do not have the permission to administer Metatag.');
    }

    $form['metatag'] = [
      '#type' => 'details',
      '#title' => $this->t('Configure Metatag default templates'),
      '#markup' => $metatag_description,
      '#open' => TRUE,
    ];

    // Add to the page the Yoast SEO form which allows the administrator
    // to enable/disable Yoast SEO by bundles.
    $config_form = \Drupal::formBuilder()
      ->getForm('Drupal\yoast_seo\Form\YoastSeoConfigForm');
    $form['yoast_seo'] = [
      '#type' => 'details',
      '#title' => 'Configure Real-time SEO by bundles',
      '#description' => 'Select the bundles Real-time SEO will be enabled for',
      '#markup' => render($config_form),
      '#open' => TRUE,
    ];

    return $form;
  }

}
