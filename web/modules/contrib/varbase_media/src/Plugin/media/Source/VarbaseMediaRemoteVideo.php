<?php

namespace Drupal\varbase_media\Plugin\media\Source;

use Drupal\media\MediaTypeInterface;
use Drupal\entity_browser_generic_embed\InputMatchInterface;
use Drupal\media\Plugin\media\Source\OEmbed as DrupalCoreOEmbed;
use Drupal\media\Plugin\Validation\Constraint\OEmbedResourceConstraint;

/**
 * Input-matching version of the Varbase Media Remote Video media source.
 */
class VarbaseMediaRemoteVideo extends DrupalCoreOEmbed implements InputMatchInterface {

  /**
   * {@inheritdoc}
   */
  public function appliesTo($value, MediaTypeInterface $bundle) {
    $url = $this->toString($value);

    $constraint = new OEmbedResourceConstraint();

    // Ensure that the URL matches a provider.
    try {
      $provider = $this->urlResolver->getProviderByUrl($url);
    }
    catch (ResourceException $e) {
      $this->handleException($e, $constraint->unknownProviderMessage);
      return FALSE;
    }
    catch (ProviderException $e) {
      $this->handleException($e, $constraint->providerErrorMessage);
      return FALSE;
    }

    // Ensure that the provider is allowed.
    if (!in_array($provider->getName(), $this->getProviders(), TRUE)) {
      return FALSE;
    }

    try {
      $endpoints = $provider->getEndpoints();
      $resource_url = reset($endpoints)->buildResourceUrl($url);
      $this->resourceFetcher->fetchResource($resource_url);

      return TRUE;
    }
    catch (ResourceException $e) {
      $this->handleException($e, $constraint->invalidResourceMessage);
    }

    return FALSE;

  }

  /**
   * Safely converts a value to a string.
   *
   * The value is converted if it is either scalar, or an object with a
   * __toString() method.
   *
   * @param mixed $value
   *   The value to convert.
   *
   * @return string|null
   *   The string representation of the value, or NULL if the value cannot be
   *   converted to a string.
   */
  protected function toString($value) {
    return is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))
      ? (string) $value
      : NULL;
  }

}
