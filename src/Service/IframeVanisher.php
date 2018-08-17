<?php

namespace Backdrop\gdpr_cookies\Service;

use Backdrop\gdpr_cookies\Entity\ThirdPartyServiceEntityInterface;

/**
 * Class IframeVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
abstract class IframeVanisher implements ThirdPartyServicesVanisherInterface, IframeVanisherInterface {

  /**
   * An array with replacement scripts.
   *
   * @var array
   */
  protected $replacementScripts = array();

  /**
   * The third party services vanisher.
   *
   * @var \Backdrop\gdpr_cookies\Service\ThirdPartyServicesVanisher
   */
  protected $vanisher;

  /**
   * EmbeddedVideoVanisher constructor.
   *
   * @param \Backdrop\gdpr_cookies\Service\ThirdPartyServicesVanisher $vanisher
   *   The third party services vanisher.
   */
  public function __construct(ThirdPartyServicesVanisher $vanisher) {
    $this->vanisher = $vanisher;
  }

  /**
   * Returns the regular expression pattern to search for the iframe.
   *
   * @return string
   *   The regular expression pattern.
   */
  abstract protected function getIframeSearchRegexPattern();

  /**
   * Returns the name of the iframe.
   *
   * @return string
   *   The name of the iframe.
   */
  public function getIframeName() {
    return 'iframe';
  }

  /**
   * Returns the privacy url of the iframe.
   *
   * @return string
   *   The privacy url.
   */
  public function getIframePrivacyUrl() {
    return '';
  }

  /**
   * Returns an array with cookies.
   *
   * @return array
   *   The cookies.
   */
  public function getIframeCookies() {
    return [];
  }

  /**
   * Returns the data of an iframe.
   *
   * @param string $iframe
   *   The markup of the iframe.
   *
   * @return array
   *   The data of the iframe.
   */
  protected function getIframeData($iframe) {
    $data = array();
    $matches = array();

    $ret = preg_match_all(ThirdPartyServicesVanisher::FIND_MARKUP_ATTRIBUTES_REGEX, $iframe, $matches);
    if ($ret !== FALSE && $ret > 0) {
      $data = array_combine($matches[1], $matches[4]);

      unset($data['iframe']);
    }

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function vanish(&$content) {
    $iframes = $this->vanisher->findInContent($this->getIframeSearchRegexPattern(), $content);

    foreach ($iframes as $iframe) {
      $replacement_markup = $this->getReplacementMarkup(
        $this->getIframeData($iframe),
        $this->vanisher->getEntity()
      );

      // Replace the iframe with the new markup.
      $content = str_replace($iframe, $replacement_markup, $content);
    }

    $this->replacementScripts[] = $this->getReplacementScript();

    return implode("\n", $this->replacementScripts);
  }

  /**
   * Returns the replacement script.
   *
   * @return string
   *   The replacement script.
   */
  protected function getReplacementScript() {
    return <<< EOF
var tarteaucitron_interval = setInterval(function() {
            if (typeof tarteaucitron.services.iframe.name == 'undefined') {
                return;
            }
            clearInterval(tarteaucitron_interval);
            
            tarteaucitron.services.iframe.name = '{$this->getIframeName()}';
            tarteaucitron.services.iframe.uri = '{$this->getIframePrivacyUrl()}';
            tarteaucitron.services.iframe.cookies = {$this->createCookiesString($this->getIframeCookies())};
        }, 10);
        (tarteaucitron.job = tarteaucitron.job || []).push('{$this->getIframeName()}');
EOF;
  }

  /**
   * Creates a string representation for an array of cookies.
   *
   * @param array $cookies
   *   The array of cookies.
   *
   * @return string
   *   The string representation of the cookies.
   */
  protected function createCookiesString(array $cookies) {
    foreach ($cookies as &$cookie) {
      $cookie = '\'' . $cookie . '\'';
    }

    $cookies_string = '[' . implode(', ', $cookies) . ']';
    return $cookies_string;
  }

  /**
   * Returns the replacement markup.
   *
   * @param array $data
   *   The array containing the iframe data.
   * @param \Backdrop\gdpr_cookies\Entity\ThirdPartyServiceEntityInterface $entity
   *   The third party service entity.
   *
   * @return string
   *   The markup replacement.
   */
  protected function getReplacementMarkup(array $data, ThirdPartyServiceEntityInterface $entity) {
    return str_replace(
      [
        '@width',
        '@height',
        '@src',
      ],
      [
        $data['width'],
        $data['height'],
        $data['src'],
      ],
      $this->getReplacementMarkupTemplate()
    );
  }

  /**
   * Returns the replacement markup template.
   *
   * @return string
   *   The replacement markup template.
   */
  protected function getReplacementMarkupTemplate() {
    return '<div class="tac_iframe" width="@width" height="@height" data-url="@src"></div>';
  }

  /**
   * Sets the current third party services entity.
   *
   * @param \Backdrop\gdpr_cookies\Entity\ThirdPartyServiceEntityInterface $entity
   *   The current third party services entity.
   */
  public function setEntity(ThirdPartyServiceEntityInterface $entity) {
    $this->vanisher->setEntity($entity);
  }

}
