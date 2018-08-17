<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class FacebookFriendboxVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class FacebookFriendboxVanisher extends ThirdPartyServicesVanisher implements ThirdPartyServicesVanisherInterface {

  const FIND_FB_FRIEND_BOX_REGEX = '~<(div).*?data-href=([\'"])[^\2]*?facebook[^\2]*?\2>.*?</\1>~is';

  /**
   * {@inheritdoc}
   */
  public function vanish(&$content) {
    $script = $this->getScript('connect.facebook.net', $this->getAllScripts($content));

    // Remove the script from the content.
    $content = $this->removeScript($script, $content);

    // Get all facebook likeboxes.
    $likeboxes = $this->findLikeboxes($content);
    foreach ($likeboxes as $likebox) {
      $replacement_markup = preg_replace('~(<div.*?class=)([\'"])(.*?)\2~is', '\1\2\3 fb-like-box\2', $likebox);

      // Replace the likebox markup with the new markup.
      $content = str_replace($likebox, $replacement_markup, $content);
    }

    return $this->getReplacementScript();
  }

  /**
   * Finds all facebook likeboxes.
   *
   * @param string $content
   *   The content to search in.
   *
   * @return array
   *   An array with likeboxes markup.
   */
  protected function findLikeboxes($content) {
    // Get all divs.
    $divs = $this->findInContent('~<div.*?>.*?</div>~is', $content);

    $likeboxes = array();
    foreach ($divs as $div) {
      $likebox = $this->findInContent(self::FIND_FB_FRIEND_BOX_REGEX, $div);
      if ($likebox != array()) {
        $likeboxes[] = $likebox[0];
      }
    }

    return $likeboxes;
  }

  /**
   * {@inheritdoc}
   */
  public function findInContent($pattern, $content) {
    $matches = array();
    $ret = preg_match_all($pattern, $content, $matches);
    if ($ret !== FALSE && $ret > 0) {
      return $matches[0];
    }

    return [];
  }

  /**
   * Returns the replacement script.
   *
   * @return string
   *   The replacement script.
   */
  public function getReplacementScript() {
    return '(tarteaucitron.job = tarteaucitron.job || []).push(\'facebooklikebox\');';
  }

  /**
   * Returns the vanisher name.
   *
   * @return string
   *   The vanisher name.
   */
  public function getVanisherName() {
    return 'facebook_friendbox_vanisher';
  }

  /**
   * Returns the name of this vanisher.
   *
   * @return string
   *   The name of this vanisher.
   */
  public function __toString() {
    return 'Facebook Friendbox (aka Likebox)';
  }

}
