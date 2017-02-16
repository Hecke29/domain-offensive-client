<?php

namespace Hecke29\DomainOffensiveClient\Tests;

use Hecke29\DomainOffensiveClient\Exception\InvalidContactException;
use Hecke29\DomainOffensiveClient\Model\Contact;
use Hecke29\DomainOffensiveClient\Service\Client\AuthenticationClient;
use Hecke29\DomainOffensiveClient\Service\Client\ContactClient;
use Hecke29\DomainOffensiveClient\Service\ContactService;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactServiceTest extends \PHPUnit_Framework_TestCase
{
  public function testCreate() {
    $handle = 'SB1234567@HANDLES.DE';

    $contact = $this->getMockBuilder(Contact::class)
                    ->setMethods(['setHandle'])
                    ->getMock();
    $contact->expects($this->once())
            ->method('setHandle')
            ->with($this->equalTo($handle));

    $contact = $this->getValidContact($contact);

    $validator = $this->createMock(ValidatorInterface::class);
    $validator->expects($this->once())
              ->method('validate')
              ->with($this->equalTo($contact))
              ->will($this->returnValue([]));

    $client = $this->createMock(ContactClient::class);
    $client->expects($this->once())
           ->method('createContact')
           ->with(
             $this->equalTo($contact->getCompany()),
             $this->equalTo($contact->getFirstname()),
             $this->equalTo($contact->getLastname()),
             $this->equalTo($contact->getStreet()),
             $this->equalTo($contact->getZipCode()),
             $this->equalTo($contact->getCity()),
             $this->equalTo($contact->getCountry()),
             $this->equalTo($contact->getPhone()),
             $this->equalTo($contact->getFax()),
             $this->equalTo($contact->getMail()),
             $this->equalTo($contact->getState()),
             $this->equalTo($contact->getTaxId()),
             $this->equalTo(
               $contact->getBirthday()
                       ->format('c')
             ),
             $this->equalTo($contact->getRegisterId())
           )
           ->will($this->returnValue($handle));

    $auth = $this->getAuthClient();
    $auth->expects($this->once())
         ->method('authenticatePartner');

    /** @var AuthenticationClient $auth */
    $service = new ContactService($validator, $auth, $client);

    $this->assertSame($contact, $service->create($contact));
  }

  public function testCreateValidationFailed() {
    $contact = $this->getMockBuilder(Contact::class)
                    ->setMethods(null)
                    ->getMock();
    $contact = $this->getValidContact($contact);

    /** Break it */
    $contact->setFirstname('');

    $validator = $this->createMock(ValidatorInterface::class);
    $validator->expects($this->once())
              ->method('validate')
              ->will(
                $this->returnValue(
                  new ConstraintViolationList(
                    [
                      new ConstraintViolation(
                        "This value should not be blank.",
                        "This value should not be blank.",
                        [],
                        $contact,
                        'firstname',
                        $contact->getFirstname()
                      )
                    ]
                  )
                )
              );

    $client = $this->createMock(ContactClient::class);
    $client->expects($this->never())
           ->method('createContact');

    $auth = $this->getAuthClient();

    /** @var AuthenticationClient $auth */
    $service = new ContactService($validator, $auth, $client);

    $this->expectException(InvalidContactException::class);
    $service->create($contact);
  }

  public function testGetSingle() {
    $handle = 'SB1234567@HANDLES.DE';

    $validator = $this->createMock(ValidatorInterface::class);
    $auth = $this->getAuthClient();

    $client = $this->createMock(ContactClient::class);
    $client->expects($this->once())
           ->method('get')
           ->with($this->equalTo($handle))
           ->will(
             $this->returnValue(
               [
                 'result'    => 'success',
                 'handle'    => 'SB1234567@HANDLES.DE',
                 'company'   => 'Musterfirma',
                 'firstname' => 'Max',
                 'lastname'  => 'Mustermann',
                 'address'   => 'Musterstrasse 123',
                 'pcode'     => '12345',
                 'city'      => 'Musterstadt',
                 'country'   => 'DE',
                 'state'     => '',
                 'telefon'   => '+49 123 456789',
                 'fax'       => '+49 123 987654',
                 'email'     => 'max@mustermann.de'
               ]
             )
           );

    /** @var AuthenticationClient $auth */
    $service = new ContactService($validator, $auth, $client);

    $expect = new Contact();
    $expect->setHandle($handle)
           ->setCompany('Musterfirma')
           ->setFirstname('Max')
           ->setLastname('Mustermann')
           ->setStreet('Musterstrasse')
           ->setHouseNumber('123')
           ->setZipCode('12345')
           ->setCity('Musterstadt')
           ->setCountry('DE')
           ->setState('')
           ->setPhone('+49 123 456789')
           ->setFax('+49 123 987654')
           ->setMail('max@mustermann.de');

    $this->assertEquals($expect, $service->get($handle));

  }

  private function getValidContact($contact) {
    /** @var Contact $contact */
    $contact->setFirstname('Fulton');
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

  /**
   * @return \PHPUnit_Framework_MockObject_MockObject
   */
  private function getAuthClient() {
    $auth = $this->getMockBuilder(AuthenticationClient::class)
                 ->disableOriginalConstructor()
                 ->setMethods(['authenticatePartner'])
                 ->getMock();

    return $auth;
  }
}
