<?php
/**
 * @author Timo Förster <foerster@silpion.de>
 * @date   28.02.17
 */

namespace Hecke29\DomainOffensiveClient\Service\Client;

use Hecke29\DomainOffensiveClient\Service\AbstractClient;

/**
 * Class DomainClient
 * @package Hecke29\DomainOffensiveClient\Service\Client
 */
class DomainClient extends AbstractClient
{

  /**
   * Registers a domain for one year
   *
   * @param        $domain
   * @param        $owner
   * @param        $admin
   * @param        $tech
   * @param        $zone
   * @param        $nameServers
   * @param int    $period
   * @param string $unit
   */
  public function create($domain, $owner, $admin, $tech, $zone, $nameServers, $period = 1, $unit = 'y') {
    $this->soapClient->__soapCall(
      'createDomain',
      [
        $domain,
        $owner,
        $admin,
        $tech,
        $zone,
        $nameServers,
        ['period' => $period, 'period-unit' => $unit, 'sandbox' => true],
        ['sandbox' => true]
      ]
    );
  }
}