<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class MatomoVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class MatomoVanisher extends ThirdPartyServicesVanisher implements ThirdPartyServicesVanisherInterface {

  const FIND_MATOMO_ID_REGEX = '~_paq\.push\(.*?([\'"])setSiteId[^\1],.*?\1(\d+)\1~i';
  const FIND_MATOMO_HOST_REGEX = '~u=.*?([\'"]).*?(\/\/.*?)\1~is';
  const FIND_MATOMO_PARAMETERS_REGEX = '~_paq\.push\(\["[^"]*?(?<!setSiteId|setTrackerUrl)".*?\);{0,1}~is';

  /**
   * {@inheritdoc}
   */
  public function vanish(&$content) {
    $replaced_script = array();
    $script = $this->getScript('piwik.php', $this->getAllScripts($content));

    if ($script) {
      $data = $this->getData($script);

      if ($data) {
        $replaced_script[] = $this->getReplacementScript($data);

        // Remove the original script.
        $content = $this->removeScript($script, $content);

        // Remove the piwik script loaded by the piwik module if installed.
        $content = $this->removeByRegex('~(<script.*?piwik\.js.*?><\/script>)~i', $content);
      }
    }

    $replaced_script[] = '(tarteaucitron.job = tarteaucitron.job || []).push(\'matomo\');';

    return implode("\n", $replaced_script);
  }

  /**
   * Returns the matomo data.
   *
   * @param string $script
   *   The matomo script.
   *
   * @return array
   *   The matomo data.
   *
   * @throws \Exception
   *   When the matomo id could not be found in the script.
   */
  protected function getData($script) {
    $data = array(
      'matomo_id' => $this->findMatomoId($script),
      'matomo_host' => $this->findMatomoHost($script),
    );

    $parameters = $this->findMatomoParameters($script);
    if (is_array($parameters)) {
      $parameters = implode("\n", $parameters);
      $data['matomo_parameters'] = $parameters;
    }

    return $data;
  }

  /**
   * Finds the matomo id in the script.
   *
   * @param string $script
   *   The matomo script.
   *
   * @return string
   *   The matomo id.
   *
   * @throws \Exception
   *   When the matomo id could not be found.
   */
  protected function findMatomoId($script) {
    $matches = array();
    $ret = preg_match(self::FIND_MATOMO_ID_REGEX, $script, $matches);
    if ($ret === FALSE || $ret < 1) {
      throw new \Exception('Failed to find mandatory matomo id.');
    }

    return $matches[2];
  }

  /**
   * Finds the matomo host in the script.
   *
   * @param string $script
   *   The matomo script.
   *
   * @return string
   *   The matomo host or an empty string.
   */
  protected function findMatomoHost($script) {
    $matches = array();
    $ret = preg_match(self::FIND_MATOMO_HOST_REGEX, $script, $matches);
    if ($ret === 1) {
      return $matches[2];
    }

    return '';
  }

  /**
   * Finds the additional parameters for matomo in the script.
   *
   * @param string $script
   *   The matomo script.
   *
   * @return array
   *   An array of matomo parameters or an empty array.
   */
  protected function findMatomoParameters($script) {
    $matches = array();
    $ret = preg_match_all(self::FIND_MATOMO_PARAMETERS_REGEX, $script, $matches);
    if ($ret !== FALSE && $ret > 0) {
      return $matches[0];
    }

    return $matches;
  }

  /**
   * Returns the replacement script.
   *
   * @param array $data
   *   The matomo data.
   *
   * @return string
   *   The replacement script.
   */
  public function getReplacementScript(array $data) {
    if (!isset($data['matomo_id'])) {
      throw new \InvalidArgumentException('The array key "matomo_id" is missing.');
    }
    if (!isset($data['matomo_host'])) {
      throw new \InvalidArgumentException('The array key "matomo_host" is missing.');
    }
    if (!isset($data['matomo_parameters'])) {
      throw new \InvalidArgumentException('The array key "matomo_parameters" is missing.');
    }

    return <<< EOF
tarteaucitron.user.matomoId = '{$data['matomo_id']}';
    tarteaucitron.user.matomoHost = '{$data['matomo_host']}';
    tarteaucitron.user.matomoParameters = function () { {$data['matomo_parameters']} };
EOF;
  }

  /**
   * Returns the vanisher name.
   *
   * @return string
   *   The vanisher name.
   */
  public function getVanisherName() {
    return 'matomo_vanisher';
  }

  /**
   * Returns the name of this vanisher.
   *
   * @return string
   *   The name of this vanisher.
   */
  public function __toString() {
    return 'Matomo Vanisher';
  }

}
