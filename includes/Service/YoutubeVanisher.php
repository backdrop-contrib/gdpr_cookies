<?php

namespace Backdrop\gdpr_cookies\Service;

use Backdrop\gdpr_cookies\Entity\ThirdPartyServiceEntityInterface;

/**
 * Class YoutubeVanisher.
 * Vanisher for embedded YouTube iframes.
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
  protected function getReplacementMarkup(array $data, ThirdPartyServiceEntityInterface $entity) {
    if ($data['width'] == "100%") {
      $data['height'] = "";
      $markup = '<div class="youtube_player" videoID="' . $data['video_id'] . '" ';
      $markup .= 'width="' . $data['width'] . '" ';
      $markup .= 'style="aspect-ratio:16/9;"';

      // Add additional parameters
      if (!empty($data['start'])) {
        $markup .= ' start="' . $data['start'] . '"';
      }
      if (!empty($data['end'])) {
        $markup .= ' end="' . $data['end'] . '"';
      }
      if (!empty($data['autoplay'])) {
        $markup .= ' autoplay="' . $data['autoplay'] . '"';
      }
      if (!empty($data['loop'])) {
        $markup .= ' loop="' . $data['loop'] . '"';
      }
      if (!empty($data['mute'])) {
        $markup .= ' mute="' . $data['mute'] . '"';
      }
      if (!empty($data['controls'])) {
        $markup .= ' controls="' . $data['controls'] . '"';
      }
      if (!empty($data['showinfo'])) {
        $markup .= ' showinfo="' . $data['showinfo'] . '"';
      }

      $markup .= '></div>';
      $markup .= filter_xss_admin($entity->getInfo());
      return $markup;
    }

    $replacement = '<div class="youtube_player" videoID="@video_id" width="@width" height="@height"';

    // Add additional parameters to template
    if (!empty($data['start'])) {
      $replacement .= ' start="@start"';
    }
    if (!empty($data['end'])) {
      $replacement .= ' end="@end"';
    }
    if (!empty($data['autoplay'])) {
      $replacement .= ' autoplay="@autoplay"';
    }
    if (!empty($data['loop'])) {
      $replacement .= ' loop="@loop"';
    }
    if (!empty($data['mute'])) {
      $replacement .= ' mute="@mute"';
    }
    if (!empty($data['controls'])) {
      $replacement .= ' controls="@controls"';
    }
    if (!empty($data['showinfo'])) {
      $replacement .= ' showinfo="@showinfo"';
    }

    $replacement .= '></div>@info_text';

    return str_replace(
      [
        '@video_id',
        '@width',
        '@height',
        '@start',
        '@end',
        '@autoplay',
        '@loop',
        '@mute',
        '@controls',
        '@showinfo',
        '@info_text',
      ],
      [
        $data['video_id'],
        $data['width'],
        $data['height'],
        !empty($data['start']) ? $data['start'] : '',
        !empty($data['end']) ? $data['end'] : '',
        !empty($data['autoplay']) ? $data['autoplay'] : '',
        !empty($data['loop']) ? $data['loop'] : '',
        !empty($data['mute']) ? $data['mute'] : '',
        !empty($data['controls']) ? $data['controls'] : '',
        !empty($data['showinfo']) ? $data['showinfo'] : '',
        filter_xss_admin($entity->getInfo()),
      ],
      $replacement
    );
  }

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

    // Extract additional parameters from URL or iframe attributes
    $data = array_merge($data, $this->extractUrlParams($data['src']));
    $data = array_merge($data, $this->extractIframeAttributes($markup));

    return $data;
  }

  /**
   * Extracts URL parameters from the YouTube URL.
   *
   * @param string $url
   *   The YouTube URL.
   *
   * @return array
   *   Array of parameters.
   */
  protected function extractUrlParams($url) {
    $params = [];
    $parsed_url = parse_url($url);

    if (isset($parsed_url['query'])) {
      parse_str($parsed_url['query'], $query_params);

      // Parameters to preserve
      $preserved_params = ['start', 'end', 'autoplay', 'loop', 'mute', 'controls', 'showinfo'];

      foreach ($preserved_params as $param) {
        if (isset($query_params[$param])) {
          $params[$param] = $query_params[$param];
        }
      }

      // Convert 't' to 'start' if exists
      if (isset($query_params['t'])) {
        $t_value = preg_replace('/s$/i', '', $query_params['t']);
        $params['start'] = $t_value;
      }
    }

    return $params;
  }

  /**
   * Extracts attributes from the iframe element.
   *
   * @param string $markup
   *   The iframe markup.
   *
   * @return array
   *   Array of attributes.
   */
  protected function extractIframeAttributes($markup) {
    $attrs = [];
    $preserved_attrs = ['start', 'end', 'autoplay', 'loop', 'mute', 'controls', 'showinfo'];

    foreach ($preserved_attrs as $attr) {
      $pattern = '/' . $attr . '=["\']([^"\']*)["\']|' . $attr . '=([^\s>]*)/i';
      if (preg_match($pattern, $markup, $matches)) {
        $attrs[$attr] = !empty($matches[1]) ? $matches[1] : $matches[2];
      }
    }

    return $attrs;
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
