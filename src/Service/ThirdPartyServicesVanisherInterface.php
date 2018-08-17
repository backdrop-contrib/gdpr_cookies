<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Interface ThirdPartyServicesVanisherInterface.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
interface ThirdPartyServicesVanisherInterface {

  /**
   * Vanishes the content.
   *
   * @param string $content
   *   The content to vanish.
   *
   * @return string
   *   The vanished content.
   */
  public function vanish(&$content);

  /**
   * Returns the vanisher name.
   *
   * @return string
   *   The vanisher name.
   */
  public function getVanisherName();

  /**
   * Returns the name of this vanisher.
   *
   * @return string
   *   The name of this vanisher.
   */
  public function __toString();

}
