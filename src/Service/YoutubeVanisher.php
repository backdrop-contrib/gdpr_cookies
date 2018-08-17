<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class YoutubeVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class YoutubeVanisher extends EmbeddedVideoVanisher {

  /**
   * The regular expression to find the video id inside of a youtube url.
   *
   * @see https://stackoverflow.com/a/9102270/2779907
   */
  const YOUTUBE_VIDEO_ID_REGEX = '~^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*~i';

  /**
   * {@inheritdoc}
   */
  protected function getReplacementMarkupTemplate() {
    return '<div class="youtube_player" videoID="@video_id" width="@width" height="@height"></div>@info_text';
  }

  /**
   * {@inheritdoc}
   */
  protected function getIframeSearchRegexPattern() {
    return '~(<iframe[^>]*?src=[^>]*?youtu.*?>.*?</iframe>)~is';
  }

  /**
   * {@inheritdoc}
   */
  protected function getReplacementScript() {
    return '(tarteaucitron.job = tarteaucitron.job || []).push(\'youtube\');';
  }

  /**
   * {@inheritdoc}
   */
  protected function getVideoData($markup) {
    $data = parent::getVideoData($markup);
    $data['video_id'] = $this->extractVideoId($data['src']);

    return $data;
  }

  /**
   * Extracts the video id.
   *
   * @param string $url
   *   The video url containing the video id.
   *
   * @return string|null
   *   The video id or NULL.
   */
  protected function extractVideoId($url) {
    $matches = array();
    $ret = preg_match(self::YOUTUBE_VIDEO_ID_REGEX, $url, $matches);
    if ($ret != FALSE && $ret == 1) {
      return $matches[2];
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getVanisherName() {
    return 'youtube_vanisher';
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'Youtube Vanisher';
  }

}
