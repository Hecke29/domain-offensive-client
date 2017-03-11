<?php

namespace Hecke29\DomainOffensiveClient\Service\Client;

use Hecke29\DomainOffensiveClient\Exception\InvalidContactException;
use Hecke29\DomainOffensiveClient\Service\AbstractClient;

class ContactClient extends AbstractClient
{

  /**
   * Creates a ContactHandle and returns the handleName.
   *
   * @param       $firma
   * @param       $vorname
   * @param       $name
   * @param       $strasse
   * @param       $postleitzahl
   * @param       $stadt
   * @param       $land
   * @param       $telefon
   * @param       $telefax
   * @param       $email
   * @param       $bundesland
   * @param       $steuerID
   * @param       $geburtstag
   * @param       $handelsregister
   * @param array $extra
   *
   * @return string
   */
  public function create(
    $firma,
    $vorname,
    $name,
    $strasse,
    $postleitzahl,
    $stadt,
    $land,
    $telefon,
    $telefax,
    $email,
    $bundesland,
    $steuerID,
    $geburtstag,
    $handelsregister,
    $extra = []
  ) {
    $response = $this->soapClient->__soapCall(
      'CreateContact',
      [
        $firma,
        $vorname,
        $name,
        $strasse,
        $postleitzahl,
        $stadt,
        $land,
        $telefon,
        $telefax,
        $email,
        $bundesland,
        $steuerID,
        $geburtstag,
        $handelsregister,
        $extra
      ]
    );

    if (!$this->isSuccessfulResponse($response)) {
      $this->handleFailedResponse(
        $response,
        [
          'Ihre Angaben waren unvollständig oder nicht korrekt.' => InvalidContactException::class,
          'Telephone invalid, valid format is'                   => InvalidContactException::class
        ]
      );
    }

    $handle = $response['object'];

    return $handle;
  }

  /**
   * @param $handle
   *
   * @return array
   */
  public function get($handle) {
    $response = $this->soapClient->__soapCall('GetContactDetails', [$handle]);

    return $response;
  }

  /**
   * @return array
   */
  public function getList() {
    $response = $this->soapClient->__soapCall('GetContactList', []);

    return $response;
  }

}