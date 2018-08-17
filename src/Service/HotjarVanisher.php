<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class HotjarVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class HotjarVanisher extends ThirdPartyServicesVanisher implements ThirdPartyServicesVanisherInterface {

  /**
   * {@inheritdoc}
   */
  public function vanish(&$content) {
    $replacement_scripts = array();
    $script = $this->getScript('window,document,\'https://static.hotjar.com/c/hotjar-\'', $this->getAllScripts($content));

    if ($script) {
      $hjsv = $this->getHjsv($script);
      $hjid = $this->getHjid($script);

      if ($hjsv && $hjid) {
        $replacement_scripts[] = $this->getReplacementScript($hjsv, $hjid);

        // Remove the original script.
        $content = $this->removeScript($script, $content);
      }
    }
    $replacement_scripts[] = '(tarteaucitron.job = tarteaucitron.job || []).push(\'hotjar\');';

    return implode("\n", $replacement_scripts);
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
  public function getReplacementScript($hjsv , $hjid) {
    return 'tarteaucitron.user.hotjarId = \'' . $hjid . '\';
    tarteaucitron.user.hotjarsv = \'' . $hjsv . '\';';
  }

  /**
   * Returns the Hotjar id.
   *
   * @param string $script
   *   The script containing the Hotjar id.
   *
   * @return string
   *   The Hotjar ID.
   *
   * @throws \Exception
   *   When no Hotjar ID has been found.
   */
  protected function getHjid($script) {
    $matches = array();
    if (FALSE === preg_match("/hjid\:(.*?)\,/s", $script, $matches)) {
      throw new \Exception('Could not find google tag manager id in script.');
    }
    return $matches[1];
  }

  /**
   * Returns the Hotjar SV.
   *
   * @param string $script
   *   The script containing the Hotjar SV.
   *
   * @return string
   *   The Hotjar SV.
   *
   * @throws \Exception
   *   When no Hotjar SV has been found.
   */
  protected function getHjsv($script) {
    $matches = array();
    if (FALSE === preg_match("/hjsv\:(.*?)}/s", $script, $matches)) {
      throw new \Exception('Could not find Hotjar SV  in script.');
    }
    return $matches[1];
  }

  /**
   * {@inheritdoc}
   */
  public function getVanisherName() {
    return 'hotjar_vanisher';
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'Hotjar';
  }

}
