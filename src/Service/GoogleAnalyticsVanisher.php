<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class GoogleAnalyticsVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class GoogleAnalyticsVanisher extends ThirdPartyServicesVanisher implements ThirdPartyServicesVanisherInterface {

  const FIND_CUSTOM_PARAMETERS_REGEX = '~ga\("[^"]*?(?<!send|create)".*?\);{0,1}~is';

  const FIND_ACCOUNT_ID_REGEX = '~"(UA.*?)"~s';

  /**
   * {@inheritdoc}
   */
  public function vanish(&$content) {
    $replacement_script = array();

    $script = $this->getScript('GoogleAnalyticsObject', $this->getAllScripts($content));
    if ($script) {
      $data = $this->extractData($script);

      // Remove the original script.
      $content = $this->removeScript($script, $content);

      $replacement_script[] = $this->getReplacementScript($data);
    }

    $replacement_script[] = '(tarteaucitron.job = tarteaucitron.job || []).push(\'analytics\');';

    return implode("\n", $replacement_script);
  }

  /**
   * Returns the replacement script.
   *
   * @param array $data
   *   The data to pass into the script.
   *
   * @return string
   *   The replacement script.
   */
  protected function getReplacementScript(array $data) {
    $ga_more = implode("\n", $data['google_analytics_more']);

    $ga_cookie_domain = isset($data['google_analytics_cookie_domain']) ?
      $data['google_analytics_cookie_domain'] : 'auto';

    return <<< EOF
        tarteaucitron.user.analyticsUa = '{$data['google_analytics_id']}';
        tarteaucitron.user.analyticsCookieDomain = '{$ga_cookie_domain}';
        tarteaucitron.user.analyticsMore = function () { {$ga_more} };
EOF;
  }

  /**
   * Extracts and returns all the data from the google analytics script.
   *
   * @param string $script
   *   The google analytics script.
   *
   * @return array
   *   The extracted data.
   *
   * @throws \Exception
   *   When no google analytics account id has been found.
   */
  protected function extractData($script) {
    $account_id = $this->getAccountId($script);
    $more = $this->getCustomParameters($script);
    $cookie_domain = $this->getCookieDomainParameterValue($script);

    return [
      'google_analytics_id' => $account_id,
      'google_analytics_more' => $more,
      'google_analytics_cookie_domain' => $cookie_domain,
    ];
  }

  /**
   * Returns the value for the cookie domain parameter.
   *
   * @param string $script
   *   The google analytics script.
   *
   * @return string
   *   The cookie domain value or an empty string.
   */
  protected function getCookieDomainParameterValue($script) {
    $matches = array();
    $ret = preg_match('~"cookieDomain":"(.*?)"~is', $script, $matches);
    if ($ret == 1) {
      return $matches[1];
    }

    return '';
  }

  /**
   * Returns the account id from the script.
   *
   * @param string $script
   *   The script.
   *
   * @return string
   *   The google analytics account id.
   *
   * @throws \Exception
   *   When no account id could be found in the script.
   */
  public function getAccountId($script) {
    $matches = array();
    if (FALSE === preg_match(self::FIND_ACCOUNT_ID_REGEX, $script, $matches)) {
      throw new \Exception('Could not find account id in google analytics script.');
    }

    return $matches[1];
  }

  /**
   * Returns the custom google analytics parameters.
   *
   * @param string $script
   *   The google analytics script.
   *
   * @return array
   *   The matches.
   */
  protected function getCustomParameters($script) {
    $matches = array();
    $ret = preg_match_all(self::FIND_CUSTOM_PARAMETERS_REGEX, $script, $matches);
    if ($ret !== FALSE && $ret > 0) {
      return $matches[0];
    }

    return $matches;
  }

  /**
   * Returns the vanisher name.
   *
   * @return string
   *   The vanisher name.
   */
  public function getVanisherName() {
    return 'google_analytics_vanisher';
  }

  /**
   * Returns the name of this vanisher.
   *
   * @return string
   *   The name of this vanisher.
   */
  public function __toString() {
    return 'Google Analytics Vanisher';
  }

}
