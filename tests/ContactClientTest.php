<?php


namespace Hecke29\DomainOffensiveClient\Tests;

use Hecke29\DomainOffensiveClient\Exception\InvalidContactException;
use Hecke29\DomainOffensiveClient\Service\Client\ContactClient;

class ContactClientTest extends \PHPUnit_Framework_TestCase
{
    private static $contacts = [['SB1234567@HANDLES.DE', 'Fulton', 'Bolton'],
        ['SB1234568@HANDLES.DE', 'Hänna', 'Montäna'],
        ['SB1234569@HANDLES.DE', 'Let it', 'Beé']];

    public function testCreate()
    {
        $handle = 'SB1234567@HANDLES.DE';

        $soap = $this->getSoapClient('CreateContact', ['result' => 'success',
            'description' => 'This text is not necessary',
            'object' => $handle],
            [null, null, null, null, null, null, null, null, null, null, null, null, null, null, []]);
        $client = new ContactClient($soap);

        $actual = $client->createContact(null, null, null, null, null, null, null, null, null, null, null, null, null, null);
        $this->assertEquals($handle, $actual);
    }

    /**
     * @param $calledMethod
     * @param $result
     * @param $parameters
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSoapClient($calledMethod, $result, $parameters = [])
    {
        $soap = $this->getMockBuilder(\SoapClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $soap->expects($this->once())
            ->method('__soapCall')
            ->with($this->equalTo($calledMethod),
                $this->equalTo($parameters))
            ->willReturn($result);

        return $soap;
    }

    public function testCreateFailedInvalidFirstName()
    {
        $message = 'Ihre Angaben waren unvollständig oder nicht korrekt.; firstname';
        $soap = $this->getSoapClient('CreateContact', ['result' => 'failed',
            'description' => $message],
            [null, null, null, null, null, null, null, null, null, null, null, null, null, null, []]);
        $client = new ContactClient($soap);

        $this->expectException(InvalidContactException::class);
        $this->expectExceptionMessage($message);
        $client->createContact(null, null, null, null, null, null, null, null, null, null, null, null, null, null);
    }

    public function testCreateFailedInvalidTelephone()
    {
        $message = 'Telephone invalid, valid format is +49 30 1234568';
        $soap = $this->getSoapClient('CreateContact', ['result' => 'failed',
            'description' => $message],
            [null, null, null, null, null, null, null, null, null, null, null, null, null, null, []]);
        $client = new ContactClient($soap);

        $this->expectException(InvalidContactException::class);
        $this->expectExceptionMessage($message);
        $client->createContact(null, null, null, null, null, null, null, null, null, null, null, null, null, null);
    }

    public function testGetList()
    {
        $soap = $this->getSoapClient('GetContactList', self::$contacts);
        $client = new ContactClient($soap);

        $this->assertEquals(self::$contacts, $client->getList());
    }

    public function testGet()
    {
        $handle = 'SB1234567@HANDLES.DE';
        $contact = [
            'result' => 'success',
            'handle' => 'SB1234567@HANDLES.DE',
            'company' => 'Musterfirma',
            'firstname' => 'Max',
            'lastname' => 'Mustermann',
            'address' => 'Musterstrasse 123',
            'pcode' => '12345',
            'city' => 'Musterstadt',
            'country' => 'DE',
            'state' => '',
            'telefon' => '+49 123 456789',
            'fax' => '+49 123 987654',
            'email' => 'max@mustermann.de'
        ];
        $soap = $this->getSoapClient('GetContactDetails', $contact, [$handle]);

        $client = new ContactClient($soap);

        $this->assertEquals($contact, $client->get($handle));
    }

}
