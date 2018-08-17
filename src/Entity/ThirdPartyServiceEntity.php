<?php

class ThirdPartyServiceEntity extends \Entity implements \Backdrop\gdpr_cookies\Entity\ThirdPartyServiceEntityInterface {

  /**
   * {@inheritdoc}
   */
  public function isEnabled() {
    return $this->enabled;
  }

  /**
   * {@inheritdoc}
   */
  public function getVanisher() {
    return $this->vanisher;
  }

  /**
   * Implements EntityInterface::id().
   */
  public function id() {
    return $this->id;
  }

  /**
   * Implements EntityInterface::entityType().
   */
  public function entityType() {
    return 'ThirdPartyService';
  }

  /**
   * Implements EntityInterface::label().
   */
  public function label() {
    return $this->label;
  }

  /**
   * Implements EntityInterface::isNew().
   */
  public function isNew() {
    return !empty($this->is_new) || !$this->id();
  }

  /**
   * Implements EntityInterface::uri().
   */
  public function uri() {
    // Anonymous users do not have a URI.
    $uri = FALSE;
    if ($this->uid) {
      $uri = array(
        'path' => 'user/' . $this->uid,
        'options' => array(),
      );
    }
    return $uri;
  }


  public function getName(){
    return $this->name;
  }

  public function getInfo(){
    return $this->info;
  }

}
