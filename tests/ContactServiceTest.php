<?php


namespace Hecke29\DomainOffensiveClient\Tests;


use Hecke29\DomainOffensiveClient\Exception\InvalidContactException;
use Hecke29\DomainOffensiveClient\Model\Contact;
use Hecke29\DomainOffensiveClient\Service\Client\ContactClient;
use Hecke29\DomainOffensiveClient\Service\ContactService;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
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
                $this->equalTo($contact->getBirthday()),
                $this->equalTo($contact->getRegisterId())
            )
            ->will($this->returnValue($handle));


        $service = new ContactService($validator, $client);

        $this->assertSame($contact, $service->create($contact));
    }

    private function getValidContact($contact)
    {

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

    public function testCreateValidationFailed()
    {
        $contact = $this->getMockBuilder(Contact::class)
            ->setMethods(null)
            ->getMock();
        $contact = $this->getValidContact($contact);

        /** Break it */
        $contact->setFirstname('');

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList(
                [
                    new ConstraintViolation("This value should not be blank.",
                        "This value should not be blank.",
                        [], $contact, 'firstname', $contact->getFirstname())
                ]
            )));

        $client = $this->createMock(ContactClient::class);
        $client->expects($this->never())
            ->method('createContact');

        $service = new ContactService($validator, $client);

        $this->expectException(InvalidContactException::class);
        $service->create($contact);
    }
}
