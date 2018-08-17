<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class GpsiesVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class TwitterTimelineVanisher extends ThirdPartyServicesVanisher implements ThirdPartyServicesVanisherInterface {

  /**
   * {@inheritdoc}
   */
  public function vanish(&$content) {
    $replaced_scripts = array();

    $scripts = $this->getScripts('platform.twitter.com/widgets.js', $this->getAllScripts($content));

    foreach ($scripts as $script) {
      $content = $this->removeScript($script, $content);
    }

    $twitter_links = $this->getTwitterLink($content);
    foreach ($twitter_links as $links) {
      $content = str_replace($links, '<span class="tacTwitterTimelines"></span>' . $links, $content);
    }


    $replaced_scripts[] = $this->getReplacementScript();
    return implode("\n", $replaced_scripts);
  }

  /**
   * Returns the replacement script.
   *
   * @return string
   *   The replacement script.
   */
  public function getReplacementScript() {
    return '(tarteaucitron.job = tarteaucitron.job || []).push(\'twittertimeline\');';
  }

  /**
   * Returns the twitter links.
   *
   * @param string $html
   *   The html string containing the scripts.
   *
   * @return array
   *   The twitter timeline links.
   */
  protected function getTwitterLink($html) {
    $matches = $this->getATags($html);
    $links = $this->getScripts('class="twitter-timeline"', $matches);
    return $links;
  }

  /**
   * {@inheritdoc}
   */
  public function getVanisherName() {
    return 'twitter_timeline_vanisher';
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'Twitter Timeline Vanisher';
  }

  /**
   * Returns an array with all a.
   *
   * @param string $html
   *   The html string containing the scripts.
   *
   * @return array
   *   The detected scripts.
   */
  protected function getATags($html) {
    $scripts = array();
    preg_match_all('/<a.*?>.*?<\/a>/s', $html, $scripts);

    return reset($scripts);
  }

}
