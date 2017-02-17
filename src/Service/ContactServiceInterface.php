<?php
/**
 * @author Timo Förster <foerster@silpion.de>
 * @date   16.02.17
 */

namespace Hecke29\DomainOffensiveClient\Service;

use Hecke29\DomainOffensiveClient\Exception\AuthenticationException;
use Hecke29\DomainOffensiveClient\Model\Contact;

/**
 * Interface ContactServiceInterface
 * @package Hecke29\DomainOffensiveClient\Service
 */
interface ContactServiceInterface
{

  /**
   * Returns a Contact with handle filled
   *
   * @param Contact $contact
   *
   * @return Contact|null
   */
  public function ensureContact(Contact $contact);

  /**
   * Gets a single contact by handle.
   *
   * @param $handle
   *
   * @return Contact
   */
  public function get($handle);

  /**
   * Gets a list of all clients
   *
   * @return Contact[]
   * @throws AuthenticationException
   */
  public function getList();

}