<?php
/**
 * @author Timo Förster <tfoerster@webfoersterei.de>
 * @date   28.02.17
 */

namespace Hecke29\DomainOffensiveClient\Service;

use Hecke29\DomainOffensiveClient\Model\Contact;
use Hecke29\DomainOffensiveClient\Model\Domain;

interface DomainServiceInterface
{

  /**
   * @param Domain  $domain
   * @param Contact $owner
   * @param Contact $tech
   * @param Contact $admin
   * @param Contact $zone
   *
   * @return bool
   */
  public function register(Domain $domain, Contact $owner, Contact $tech, Contact $admin, Contact $zone);

}