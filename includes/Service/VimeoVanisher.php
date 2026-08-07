<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class VimeoVanisher.
 * Vanisher for embedded Vimeo video player.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class VimeoVanisher extends EmbeddedVideoVanisher {

  /**
   * The regular expression to find the video id inside of a vimeo url.
   *
   * @see https://gist.github.com/anjan011/1fcecdc236594e6d700f
   */
  const VIMEO_VIDEO_ID_REGEX = '~vimeo\.com/(?:.*?/)?(?:video/)?(\d+)~i';

  /**
   * {@inheritdoc}
   */
  protected function getIframeSearchRegexPattern() {
    return '~(<iframe[^>]+src=([\'"])(.*?vimeo\.com\/.*?\/\d+[^\'"]*)\2[^>]*>.*?<\/iframe>)~i';
  }

  /**
   * {@inheritdoc}
   */
  protected function getReplacementScript() {
    return '(tarteaucitron.job = tarteaucitron.job || []).push(\'vimeo\');';
  }

  /**
   * {@inheritdoc}
   */
  protected function getReplacementMarkupTemplate() {
    return '<div class="vimeo_player" videoID="@video_id" width="@width" height="@height"></div>';
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
    if (preg_match(self::VIMEO_VIDEO_ID_REGEX, $url, $matches)) {
      return $matches[1] ?? null;
    }
    return null;
  }

  /**
   * {@inheritdoc}
   */
  public function getVanisherName() {
    return 'vimeo_vanisher';
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'Vimeo Vanisher';
  }

}
