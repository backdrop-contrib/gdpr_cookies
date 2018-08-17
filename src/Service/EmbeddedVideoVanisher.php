<?php

namespace Backdrop\gdpr_cookies\Service;

use Backdrop\gdpr_cookies\Entity\ThirdPartyServiceEntityInterface;

/**
 * Class EmbeddedVideoVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
abstract class EmbeddedVideoVanisher extends IframeVanisher implements IframeVanisherInterface {

  /**
   * {@inheritdoc}
   */
  protected function getReplacementMarkup(array $data, ThirdPartyServiceEntityInterface $entity) {
    return str_replace(
      [
        '@video_id',
        '@width',
        '@height',
        '@info_text',
      ],
      [
        $data['video_id'],
        $data['width'],
        $data['height'],
        $entity->getInfo(),
      ],
      $this->getReplacementMarkupTemplate()
    );
  }

  /**
   * Returns the video data found in the markup.
   *
   * @param string $markup
   *   The markup to search through.
   *
   * @return array
   *   An array with video data.
   */
  protected function getVideoData($markup) {
    $data = array();
    $matches = array();

    $ret = preg_match_all(ThirdPartyServicesVanisher::FIND_MARKUP_ATTRIBUTES_REGEX, $markup, $matches);
    if ($ret !== FALSE && $ret > 0) {
      $data = array_combine($matches[1], $matches[4]);

      unset($data['iframe']);
    }

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  protected function getIframeData($iframe) {
    return $this->getVideoData($iframe);
  }

}
