<?php

namespace Hecke29\DomainOffensiveClient\Tests;

use Hecke29\DomainOffensiveClient\Exception\ContactNotUniqueException;
use Hecke29\DomainOffensiveClient\Exception\InvalidContactException;
use Hecke29\DomainOffensiveClient\Model\Contact;
use Hecke29\DomainOffensiveClient\Model\ContactListEntry;
use Hecke29\DomainOffensiveClient\Service\Client\AuthenticationClient;
use Hecke29\DomainOffensiveClient\Service\Client\ContactClient;
use Hecke29\DomainOffensiveClient\Service\ContactMatcherServiceInterface;
use Hecke29\DomainOffensiveClient\Service\ContactService;
use Hecke29\DomainOffensiveClient\Service\RelaxedContactMatcherService;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactServiceTest extends \PHPUnit_Framework_TestCase
{
  const HANDLE = 'SB1234567@HANDLES.DE';
  const HANDLE2 = 'SB1234568@HANDLES.DE';

  public function testCreate() {

    $contact = $this->getMockBuilder(Contact::class)
                    ->setMethods(['setHandle'])
                    ->getMock();
    $contact->expects($this->once())
            ->method('setHandle')
            ->with($this->equalTo(self::HANDLE));

    $contact = $this->getValidContact($contact);

    $validator = $this->createValidatorMock();
    $validator->expects($this->once())
              ->method('validate')
              ->with($this->equalTo($contact))
              ->will($this->returnValue([]));

    $client = $this->createClientMock();
    $client->expects($this->once())
           ->method('getList')
           ->will($this->returnValue([]));
    $client->expects($this->once())
           ->method('create')
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
           ->will($this->returnValue(self::HANDLE));

    $auth = $this->createAuthMock();
    $auth->expects($this->exactly(2))
         ->method('authenticatePartner');

    /** @var AuthenticationClient $auth */
    $service = $this->createContactService($validator, $auth, $client, $this->createMatcherMock());

    $this->assertSame($contact, $service->ensureContact($contact));
  }

  public function testCreateValidationFailed() {
    $contact = $this->getMockBuilder(Contact::class)
                    ->setMethods(null)
                    ->getMock();
    $contact = $this->getValidContact($contact);

    /** Break it */
    $contact->setFirstname('');

    $validator = $this->createValidatorMock();
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

    $client = $this->createClientMock();
    $client->expects($this->once())
           ->method('getList')
           ->will($this->returnValue([]));
    $client->expects($this->never())
           ->method('create');

    $auth = $this->createAuthMock();

    /** @var AuthenticationClient $auth */
    $service = $this->createContactService($validator, $auth, $client, $this->createMatcherMock());

    $this->expectException(InvalidContactException::class);
    $service->ensureContact($contact);
  }

  public function testEnsureNotUnique() {
    $validator = $this->createValidatorMock();
    $authClient = $this->createAuthMock();
    $contactClient = $this->createClientMock();
    $matcher = $this->createMatcherMock();

    $existingContact = $this->getValidContact(new Contact());
    $existingContact->setHandle(self::HANDLE);
    $existingListResult = $this->getListEntryFromContact($existingContact);
    $existingListEntry = new ContactListEntry($existingListResult[0], $existingListResult[1], $existingListResult[2]);

    $existingContact2 = $this->getValidContact(new Contact());
    $existingContact2->setHandle(self::HANDLE2);
    $existingListResult2 = $this->getListEntryFromContact($existingContact2);
    $existingListEntry2 =
      new ContactListEntry($existingListResult2[0], $existingListResult2[1], $existingListResult2[2]);

    $newContact = $this->getValidContact(new Contact());

    $contactClient->expects($this->once())
                  ->method('getList')
                  ->willReturn([$existingListResult, $existingListResult2]);

    $contactClient->expects($this->exactly(2))
                  ->method('get')
                  ->will(
                    $this->onConsecutiveCalls(
                      [
                        'result'    => 'success',
                        'handle'    => $existingContact2->getHandle(),
                        'company'   => $existingContact2->getCompany(),
                        'firstname' => $existingContact2->getFirstname(),
                        'lastname'  => $existingContact2->getLastname(),
                        'address'   => $existingContact2->getStreet() . ' ' . $existingContact2->getHouseNumber(),
                        'pcode'     => $existingContact2->getZipCode(),
                        'city'      => $existingContact2->getCity(),
                        'country'   => $existingContact2->getCountry(),
                        'state'     => $existingContact2->getState(),
                        'telefon'   => $existingContact2->getPhone(),
                        'fax'       => $existingContact2->getFax(),
                        'email'     => $existingContact2->getMail()
                      ],
                      [
                        'result'    => 'success',
                        'handle'    => $existingContact->getHandle(),
                        'company'   => $existingContact->getCompany(),
                        'firstname' => $existingContact->getFirstname(),
                        'lastname'  => $existingContact->getLastname(),
                        'address'   => $existingContact->getStreet() . ' ' . $existingContact->getHouseNumber(),
                        'pcode'     => $existingContact->getZipCode(),
                        'city'      => $existingContact->getCity(),
                        'country'   => $existingContact->getCountry(),
                        'state'     => $existingContact->getState(),
                        'telefon'   => $existingContact->getPhone(),
                        'fax'       => $existingContact->getFax(),
                        'email'     => $existingContact->getMail()
                      ]
                    )
                  );

    $matcher->expects($this->exactly(2))
            ->method('matchList')
            ->willReturn(true);

    // HACK: No birthday on result of API
    $existingContact->setBirthday(null);
    $existingContact2->setBirthday(null);

    $matcher->expects($this->exactly(2))
            ->method('matchDetails')
            ->willReturn(true);

    $contactService = $this->createContactService($validator, $authClient, $contactClient, $matcher);

    $this->expectException(ContactNotUniqueException::class);
    $contactService->ensureContact($newContact);
  }

  public function testGetSingle() {
    $validator = $this->createValidatorMock();
    $auth = $this->createAuthMock();

    $client = $this->createClientMock();
    $client->expects($this->once())
           ->method('get')
           ->with($this->equalTo(self::HANDLE))
           ->will(
             $this->returnValue(
               [
                 'result'    => 'success',
                 'handle'    => self::HANDLE,
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
    $service = $this->createContactService($validator, $auth, $client, $this->createMatcherMock());

    $expect = new Contact();
    $expect->setHandle(self::HANDLE)
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

    $this->assertEquals($expect, $service->get(self::HANDLE));

  }

  public function testUpdateEnsure() {
    $validator = $this->createValidatorMock();
    $authClient = $this->createAuthMock();
    $contactClient = $this->createClientMock();
    $matcher = $this->createMatcherMock();

    $existingContact = $this->getValidContact(new Contact());
    $existingContact->setHandle(self::HANDLE);
    $existingListResult = $this->getListEntryFromContact($existingContact);
    $existingListEntry = new ContactListEntry($existingListResult[0], $existingListResult[1], $existingListResult[2]);
    $newContact = $this->getValidContact(new Contact());

    $contactClient->expects($this->once())
                  ->method('getList')
                  ->willReturn([$existingListResult]);
    $contactClient->expects($this->once())
                  ->method('get')
                  ->with($this->equalTo($existingListEntry->getHandle()))
                  ->willReturn(
                    [
                      'result'    => 'success',
                      'handle'    => $existingContact->getHandle(),
                      'company'   => $existingContact->getCompany(),
                      'firstname' => $existingContact->getFirstname(),
                      'lastname'  => $existingContact->getLastname(),
                      'address'   => $existingContact->getStreet() . ' ' . $existingContact->getHouseNumber(),
                      'pcode'     => $existingContact->getZipCode(),
                      'city'      => $existingContact->getCity(),
                      'country'   => $existingContact->getCountry(),
                      'state'     => $existingContact->getState(),
                      'telefon'   => $existingContact->getPhone(),
                      'fax'       => $existingContact->getFax(),
                      'email'     => $existingContact->getMail()
                    ]
                  );

    $matcher->expects($this->once())
            ->method('matchList')
            ->with($this->equalTo($existingListEntry), $this->equalTo($newContact))
            ->willReturn(true);

    $existingContact->setBirthday(null);

    $matcher->expects($this->once())
            ->method('matchDetails')
            ->with(
              $this->equalTo($existingContact),
              $this->equalTo($newContact)
            )
            ->willReturn(true);

    $contactService = $this->createContactService($validator, $authClient, $contactClient, $matcher);

    $this->assertEquals($existingContact, $contactService->ensureContact($newContact));
  }

  /**
   * @param ValidatorInterface             $validator
   * @param AuthenticationClient           $authenticationClient
   * @param ContactClient                  $contactClient
   * @param ContactMatcherServiceInterface $contactMatcher
   *
   * @return ContactService
   */
  private function createContactService($validator, $authenticationClient, $contactClient, $contactMatcher) {
    return new ContactService($validator, $authenticationClient, $contactClient, $contactMatcher);
  }

  /**
   * @return \PHPUnit_Framework_MockObject_MockObject|ValidatorInterface
   */
  private function createValidatorMock() {
    return $this->createMock(ValidatorInterface::class);
  }

  /**
   * @return \PHPUnit_Framework_MockObject_MockObject|ContactMatcherServiceInterface
   */
  private function createMatcherMock() {
    $matcher = $this->getMockBuilder(RelaxedContactMatcherService::class);

    return $matcher->getMock();
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

  private function getListEntryFromContact(Contact $contact) {
    return [$contact->getHandle(), $contact->getFirstname(), $contact->getLastname()];
  }

  /**
   * @return \PHPUnit_Framework_MockObject_MockObject|ContactClient
   */
  private function createClientMock() {
    return $this->createMock(ContactClient::class);
  }

  /**
   * @return \PHPUnit_Framework_MockObject_MockObject|AuthenticationClient
   */
  private function createAuthMock() {
    $auth = $this->getMockBuilder(AuthenticationClient::class)
                 ->disableOriginalConstructor()
                 ->setMethods(['authenticatePartner'])
                 ->getMock();

    return $auth;
  }
}
