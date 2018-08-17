<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class FacebookCommentsVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class FacebookCommentsVanisher extends ThirdPartyServicesVanisher implements ThirdPartyServicesVanisherInterface {

  /**
   * {@inheritdoc}
   */
  public function vanish(&$content) {
    $script = $this->getScript('connect.facebook.net', $this->getAllScripts($content));

    // Remove the script from the content.
    $content = $this->removeScript($script, $content);

    return $this->getReplacementScript();
  }

  /**
   * Returns the replacement script.
   *
   * @return string
   *   The replacement script.
   */
  public function getReplacementScript() {
    return '(tarteaucitron.job = tarteaucitron.job || []).push(\'facebookcomment\');';
  }

  /**
   * Returns the vanisher name.
   *
   * @return string
   *   The vanisher name.
   */
  public function getVanisherName() {
    return 'facebook_comments_vanisher';
  }

  /**
   * Returns the name of this vanisher.
   *
   * @return string
   *   The name of this vanisher.
   */
  public function __toString() {
    return 'Facebook Comments';
  }

}
