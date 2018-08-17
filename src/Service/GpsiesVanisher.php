<?php

namespace Backdrop\gdpr_cookies\Service;

/**
 * Class GpsiesVanisher.
 *
 * @package Backdrop\gdpr_cookies\Service
 */
class GpsiesVanisher extends IframeVanisher implements IframeVanisherInterface {

  /**
   * {@inheritdoc}
   */
  protected function getIframeSearchRegexPattern() {
    return '~(<iframe.*?src=([\'"])(.*?www\.gpsies\.com.*?)\2.*><\/iframe>)~i';
  }

  /**
   * {@inheritdoc}
   */
  public function getIframeName() {
    return 'gpsies';
  }

  /**
   * {@inheritdoc}
   */
  protected function getIframeData($iframe) {
    $data = parent::getIframeData($iframe);
    $data['name'] = $this->getIframeName();

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function getIframePrivacyUrl() {
    return 'http://www.gpsies.com/page.do?page=privacy';
  }

  /**
   * {@inheritdoc}
   */
  public function getIframeCookies() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getVanisherName() {
    return 'gpsies_vanisher';
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'Gpsies Vanisher';
  }

}
