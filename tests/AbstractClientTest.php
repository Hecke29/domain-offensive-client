<?php
/**
 * @author Timo Förster <foerster@silpion.de>
 * @date   28.02.17
 */

namespace Hecke29\DomainOffensiveClient\Tests;

/**
 * Class AbstractClientTest
 */
abstract class AbstractClientTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @param $calledMethod
   * @param $result
   * @param $parameters
   *
   * @return \PHPUnit_Framework_MockObject_MockObject|\SoapClient
   */
  protected function getSoapClient($calledMethod, $result, $parameters = []) {
    $soap = $this->getMockBuilder(\SoapClient::class)
                 ->disableOriginalConstructor()
                 ->getMock();
    $soap->expects($this->once())
         ->method('__soapCall')
         ->with(
           $this->equalTo($calledMethod),
           $this->equalTo($parameters)
         )
         ->willReturn($result);

    return $soap;
  }
}