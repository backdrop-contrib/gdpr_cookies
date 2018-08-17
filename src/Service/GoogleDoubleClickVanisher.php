<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class GoogleDoubleClickVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class GoogleDoubleClickVanisher extends ThirdPartyServicesVanisher implements ThirdPartyServicesVanisherInterface {

  /**
   * {@inheritdoc}
   */
  public function vanish(&$content) {
    $scripts = $this->getScripts('doubleclick.net', $this->getAllScripts($content));

    foreach ($scripts as $script) {
      $data = $this->getData($script);

      // Remove the original script from the dom.
      $content = $this->removeScript($script, $content);

      $replacement_scripts[] = $this->getReplacementScript($data);
    }

    return implode("\n", $replacement_scripts);
  }

  /**
   * Returns the data of the facebook pixel.
   *
   * @param string $script
   *   The facebook pixel javascript.
   *
   * @return array
   *   The data.
   */
  protected function getData($script) {
    if (preg_match('~//(\d+)\.fls\.doubleclick\.net~i', $script, $matches) !== FALSE) {
      return array(
        'double_click_id' => $matches[1],
      );
    }

    return array();
  }

  /**
   * Returns the replacement script.
   *
   * @param array $data
   *   The data to pass in.
   *
   * @return string
   *   The replacement script.
   */
  protected function getReplacementScript(array $data) {
    return <<< JS
tarteaucitron.user.doubleClickId = '{$data['double_click_id']}';
        (tarteaucitron.job = tarteaucitron.job || []).push('doubleclick');
JS;
  }

  /**
   * Returns the vanisher name.
   *
   * @return string
   *   The vanisher name.
   */
  public function getVanisherName() {
    return 'google_doubleclick_vanisher';
  }

  /**
   * Returns the name of this vanisher.
   *
   * @return string
   *   The name of this vanisher.
   */
  public function __toString() {
    return 'Google DoubleClick';
  }

}
