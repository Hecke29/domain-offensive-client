<?php
/**
 * @author Timo Förster <foerster@silpion.de>
 * @date   17.02.17
 */

namespace Hecke29\DomainOffensiveClient\Service;

use Hecke29\DomainOffensiveClient\Model\Contact;
use Hecke29\DomainOffensiveClient\Model\ContactListEntry;

/**
 * Interface ContactMatcherServiceInterface
 * @package Hecke29\DomainOffensiveClient\Service
 */
interface ContactMatcherServiceInterface
{

  /**
   * @param Contact $existingContact
   * @param Contact $potentialDuplicate
   *
   * @return boolean
   */
  public function matchDetails(Contact $existingContact, Contact $potentialDuplicate);

  /**
   * @param ContactListEntry $existingContact
   * @param Contact          $potentialDuplicate
   *
   * @return boolean
   */
  public function matchList(ContactListEntry $existingContact, Contact $potentialDuplicate);

}