<?php


namespace Hecke29\DomainOffensiveClient\Tests;


use BeSimple\SoapClient\SoapClient;
use Hecke29\DomainOffensiveClient\Exception\InvalidContactException;
use Hecke29\DomainOffensiveClient\Service\Client\ContactClient;

class ContactClientTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $handle = 'SB1234567@HANDLES.DE';

        $soap = $this->getSoapClient('CreateContact', ['result' => 'success',
            'description' => 'This text is not necessary',
            'object' => $handle]);
        $client = new ContactClient($soap);

        $actual = $client->createContact(null, null, null, null, null, null, null, null, null, null, null, null, null, null);
        $this->assertEquals($handle, $actual);
    }

    /**
     * @param $calledMethod
     * @param $result
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSoapClient($calledMethod, $result)
    {
        $soap = $this->getMockBuilder(SoapClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $soap->expects($this->once())
            ->method('__soapCall')
            ->with($this->equalTo($calledMethod))
            ->willReturn($result);

        return $soap;
    }

    public function testCreatedFailedInvalidFirstName()
    {
        $message = 'Ihre Angaben waren unvollständig oder nicht korrekt.; firstname';
        $soap = $this->getSoapClient('CreateContact', ['result' => 'failed',
            'description' => $message]);
        $client = new ContactClient($soap);

        $this->expectException(InvalidContactException::class);
        $this->expectExceptionMessage($message);
        $client->createContact(null, null, null, null, null, null, null, null, null, null, null, null, null, null);
    }

    public function testCreatedFailedInvalidTelephone()
    {
        $message = 'Telephone invalid, valid format is +49 30 1234568';
        $soap = $this->getSoapClient('CreateContact', ['result' => 'failed',
            'description' => $message]);
        $client = new ContactClient($soap);

        $this->expectException(InvalidContactException::class);
        $this->expectExceptionMessage($message);
        $client->createContact(null, null, null, null, null, null, null, null, null, null, null, null, null, null);
    }

}
