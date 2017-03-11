<?php
/**
 * @author Timo Förster <foerster@silpion.de>
 * @date   28.02.17
 */

namespace Hecke29\DomainOffensiveClient\Tests;

use Hecke29\DomainOffensiveClient\Service\Client\DomainClient;

class DomainClientTest extends AbstractClientTest
{

  public function testCreateDomain() {
    $soapClient = $this->getSoapClient(
      'createDomain',
      [
        'result'      => 'success',
        'description' => 'This text is not necessary',
        'object'      => '12345678'
      ],
      [
        'webfoersterei.de',
        'SB1234567@HANDLES.DE',
        'SB1234568@HANDLES.DE',
        'SB1234569@HANDLES.DE',
        'SB1234570@HANDLES.DE',
        [],
        ['period' => 1, 'period-unit' => 'y', 'sandbox' => true],
        ['sandbox' => true]
      ]
    );

    $domainClient = new DomainClient($soapClient);

    $domainClient->create(
      'webfoersterei.de',
      'SB1234567@HANDLES.DE',
      'SB1234568@HANDLES.DE',
      'SB1234569@HANDLES.DE',
      'SB1234570@HANDLES.DE',
      []
    );

  }

}
