<?php

namespace Hecke29\DomainOffensiveClient\Service;

use Hecke29\DomainOffensiveClient\Model\Contact;
use Hecke29\DomainOffensiveClient\Model\ContactListEntry;

/**
 * Class RelaxedContactMatcherService
 * @package Hecke29\DomainOffensiveClient\Service
 */
class RelaxedContactMatcherService implements ContactMatcherServiceInterface
{
  /**
   * @inheritdoc
   */
  public function matchDetails(Contact $existingContact, Contact $potentialDuplicate) {
    return ($existingContact->getHandle() == $potentialDuplicate->getHandle()
            || ($existingContact->getFirstname() == $potentialDuplicate->getFirstname()
                && $existingContact->getLastname() == $potentialDuplicate->getLastname()
                && $existingContact->getCompany() == $potentialDuplicate->getCompany()
                && $existingContact->getMail() == $potentialDuplicate->getMail()));
  }

  /**
   * @inheritdoc
   */
  public function matchList(ContactListEntry $existingContact, Contact $potentialDuplicate) {
    return (($existingContact->getHandle() == $potentialDuplicate->getHandle())
            || ($existingContact->getFirstName() == $potentialDuplicate->getFirstname()
                && $existingContact->getLastName() == $potentialDuplicate->getLastname()));
  }

}