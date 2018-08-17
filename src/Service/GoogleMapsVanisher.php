<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class GoogleMapsVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class GoogleMapsVanisher extends IframeVanisher implements ThirdPartyServicesVanisherInterface {

  /**
   * Returns the regular expression pattern to search for the iframe.
   *
   * @return string
   *   The regular expression pattern.
   */
  protected function getIframeSearchRegexPattern() {
    return '~(<iframe[^>]*?src=([\'"])[^\'"]*?google\.com/maps.*?\2.*?>.*?</iframe>)~is';
  }

  /**
   * Returns the vanisher name.
   *
   * @return string
   *   The vanisher name.
   */
  public function getVanisherName() {
    return 'google_maps_vanisher';
  }

  /**
   * Returns the name of this vanisher.
   *
   * @return string
   *   The name of this vanisher.
   */
  public function __toString() {
    return 'Google Maps';
  }

}
