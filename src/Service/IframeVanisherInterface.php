<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Interface IframeVanisherInterface.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
interface IframeVanisherInterface {

  /**
   * Returns the name of the iframe.
   *
   * @return string
   *   The name of the iframe.
   */
  public function getIframeName();

  /**
   * Returns the privacy url of the iframe.
   *
   * @return string
   *   The privacy url.
   */
  public function getIframePrivacyUrl();

  /**
   * Returns an array with cookies.
   *
   * @return array
   *   The cookies.
   */
  public function getIframeCookies();

}
