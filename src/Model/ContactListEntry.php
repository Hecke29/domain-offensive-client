<?php
/**
 * @author Timo Förster <foerster@silpion.de>
 * @date   17.02.17
 */

namespace Hecke29\DomainOffensiveClient\Model;

/**
 * Class ContactListEntry
 * @package Hecke29\DomainOffensiveClient\Model
 */
class ContactListEntry
{
  /**
   * @var string
   */
  private $firstName;

  /**
   * @var string
   */
  private $handle;

  /**
   * @var string
   */
  private $lastName;

  /**
   * ContactListEntry constructor.
   *
   * @param string $handle
   * @param string $firstName
   * @param string $lastName
   */
  public function __construct($handle, $firstName, $lastName) {
    $this->handle = $handle;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
  }

  /**
   * @return string
   */
  public function getFirstName() {
    return $this->firstName;
  }

  /**
   * @return string
   */
  public function getHandle() {
    return $this->handle;
  }

  /**
   * @return string
   */
  public function getLastName() {
    return $this->lastName;
  }

}