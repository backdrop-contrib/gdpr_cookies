<?php

namespace Backdrop\gdpr_cookies\Entity;


/**
 * Interface ThirdPartyServiceEntityInterface.
 *
 * @package Backdrop\gdpr_cookies\Entity
 */
interface ThirdPartyServiceEntityInterface {

  /**
   * Returns the entity id.
   *
   * @return string
   *   The entity id.
   */
  public function id();

  /**
   * Returns the label.
   *
   * @return string
   *   The label.
   */
  public function label();

  /**
   * Returns the name.
   *
   * @return string
   *   The name.
   */
  public function getName();

  /**
   * Returns the info content.
   *
   * @return string
   *   The info content.
   */
  public function getInfo();

  /**
   * Returns whether the service will be controlled or not.
   *
   * @return bool
   *   TRUE when control is activated, otherwise FALSE.
   */
  public function isEnabled();

  /**
   * Returns the name of the vanisher to use.
   *
   * @return string
   *   The name of the vanisher.
   */
  public function getVanisher();

}