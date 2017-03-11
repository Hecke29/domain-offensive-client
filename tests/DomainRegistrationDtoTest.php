<?php
/**
 * @author Timo Förster <tfoerster@webfoersterei.de>
 * @date   02.03.17
 */

namespace Hecke29\DomainOffensiveClient\Tests;

use Hecke29\DomainOffensiveClient\Dto\DomainRegistrationDto;
use Hecke29\DomainOffensiveClient\Model\Domain;
use Symfony\Component\Validator\Validation;

class DomainRegistrationDtoTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @return DomainRegistrationDto
   */
  private static function getValidDomainRegistrationDto() {
    $domain = new Domain();
    $domain->setDomain('webfoerstrei');
    $domain->setTld('de');

    $adminAndOwnerContact = ContactTest::getValidContact();
    $adminAndOwnerContact->setFirstname('Timo');
    $adminAndOwnerContact->setLastname('Förster');

    $techAndZoneContact = ContactTest::getValidContact();

    $registration = new DomainRegistrationDto();
    $registration->domain = $domain;
    $registration->ownerC = $adminAndOwnerContact;
    $registration->adminC = $adminAndOwnerContact;
    $registration->techC = $techAndZoneContact;
    $registration->zoneC = $techAndZoneContact;
    $registration->nameServers = ['127.0.0.1', '192.168.0.2'];

    return $registration;
  }

  public function testContactValidation() {
    $validator = $this->getValidator();
    $registration = $this->getValidDomainRegistrationDto();

    $this->assertEquals(0, count($validator->validate($registration)));
  }

  public function testNameServersValidation() {
    $validator = $this->getValidator();
    $registration = $this->getValidDomainRegistrationDto();

    $this->assertEquals(0, count($validator->validate($registration)));

    // invalid IP
    $registration->nameServers = ['127.0.0.1', '127.0.0.2', '127.0.0'];
    $this->assertEquals(1, count($validator->validate($registration)));

    // only one IP
    $registration->nameServers = ['127.0.0.1'];
    $this->assertEquals(1, count($validator->validate($registration)));
  }

  private function getValidator() {
    return Validation::createValidatorBuilder()
                     ->enableAnnotationMapping()
                     ->getValidator();
  }

}
