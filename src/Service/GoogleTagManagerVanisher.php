<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class GoogleTagManagerVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class GoogleTagManagerVanisher extends ThirdPartyServicesVanisher implements ThirdPartyServicesVanisherInterface {

  /**
   * {@inheritdoc}
   */
  public function vanish(&$content) {
    $replaced_scripts = array();

    $scripts = $this->getScripts('googletagmanager.com/gtm.js', $this->getAllScripts($content));
    foreach ($scripts as $script) {
      $gtm_id = $this->getGtmId($script);

      if ($gtm_id) {
        $replaced_scripts[] = $this->getReplacementScript($gtm_id);

        // Remove the original script.
        $content = $this->removeScript($script, $content);
      }
    }

    $replaced_scripts[] = '(tarteaucitron.job = tarteaucitron.job || []).push(\'googletagmanager\');';

    return implode("\n", $replaced_scripts);
  }

  /**
   * Returns the replacement script.
   *
   * @param string $gtm_id
   *   The google tag manager id.
   *
   * @return string
   *   The replacement script.
   */
  public function getReplacementScript($gtm_id) {
    return '(tarteaucitron.user.googletagmanagerId = tarteaucitron.user.googletagmanagerId || []).push(\'' . $gtm_id . '\');
         (tarteaucitron.job = tarteaucitron.job || []).push(\'googletagmanager\');';
  }

  /**
   * Returns the google tag manager id.
   *
   * @param string $script
   *   The script containing the google tag manager id.
   *
   * @return string
   *   The google tag manager id.
   *
   * @throws \Exception
   *   When no google tag manager id has been found.
   */
  protected function getGtmId($script) {
    $matches = array();
    if (FALSE === preg_match("~'(GTM\-.*?)'~s", $script, $matches)) {
      throw new \Exception('Could not find google tag manager id in script.');
    }

    return $matches[1];
  }

  /**
   * {@inheritdoc}
   */
  public function getVanisherName() {
    return 'google_tag_manager_vanisher';
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'Google Tag Manager Vanisher';
  }

}
