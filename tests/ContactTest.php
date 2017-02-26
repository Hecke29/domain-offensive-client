<?php

namespace Hecke29\DomainOffensiveClient\Tests;

use Hecke29\DomainOffensiveClient\Model\Contact;
use Symfony\Component\Validator\Validation;

class ContactTest extends \PHPUnit_Framework_TestCase
{

  public function testPhoneValidation() {
    $contact = $this->getValidContact();
    $validator = $this->getValidator();

    $contact->setPhone(null);
    $this->assertEquals(1, count($validator->validate($contact)));

    $contact->setPhone('');
    $this->assertEquals(1, count($validator->validate($contact)));

    $contact->setPhone('+49418223563');
    $this->assertEquals(1, count($validator->validate($contact)));

    $contact->setPhone('+49  4182  23563');
    $this->assertEquals(1, count($validator->validate($contact)));

    $contact->setPhone('+49 4 0123456');
    $this->assertEquals(1, count($validator->validate($contact)));

    $contact->setPhone('04182 0123456');
    $this->assertEquals(1, count($validator->validate($contact)));

    $contact->setPhone('+ 49 182 0123456');
    $this->assertEquals(1, count($validator->validate($contact)));

    $contact->setPhone('+49 40 123456');
    $this->assertEquals(0, count($validator->validate($contact)));

    $contact->setPhone('+49 4182 23563');
    $this->assertEquals(0, count($validator->validate($contact)));
  }

  private function getValidContact() {
    $contact = new Contact();

    /** @var Contact $contact */
    $contact->setFirstname('Folton');
    $contact->setLastname('Bolton');
    $contact->setStreet('Waldring');
    $contact->setHouseNumber('29b');
    $contact->setZipCode('22589');
    $contact->setCity('Ahrensburg');
    $contact->setState('Schleswig-Holstein');
    $contact->setBirthday(new \DateTime('1984-11-01'));
    $contact->setCountry('Germany');
    $contact->setPhone('+49 1234 5678910');
    $contact->setFax('+49 99 000999');
    $contact->setMail('mail@example.com');

    return $contact;
  }

  private function getValidator() {
    return Validation::createValidatorBuilder()
                     ->enableAnnotationMapping()
                     ->getValidator();
  }

}
