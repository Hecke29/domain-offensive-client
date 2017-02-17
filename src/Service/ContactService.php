<?php

namespace Hecke29\DomainOffensiveClient\Service;

use Hecke29\DomainOffensiveClient\Exception\InvalidContactException;
use Hecke29\DomainOffensiveClient\Model\Contact;
use Hecke29\DomainOffensiveClient\Model\ContactListEntry;
use Hecke29\DomainOffensiveClient\Service\Client\AuthenticationClient;
use Hecke29\DomainOffensiveClient\Service\Client\ContactClient;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactService implements ContactServiceInterface
{
  /**
   * @var AuthenticationClient
   */
  private $authenticationClient;

  /**
   * @var ContactClient
   */
  private $contactClient;

  /**
   * @var ValidatorInterface
   */
  private $validator;

  public function __construct(
    ValidatorInterface $validator,
    AuthenticationClient $authenticationClient,
    ContactClient $contactClient
  ) {
    $this->validator = $validator;
    $this->authenticationClient = $authenticationClient;
    $this->contactClient = $contactClient;
  }

  /**
   * @@inheritdoc
   */
  public function ensureContact(Contact $contact) {
    if (!$contact->getHandle()) {
      $result = $this->findContact($contact, $this->getList());

      if (!$result) {
        return $this->create($contact);
      } else {
        return $result;
      }
    } else {
      // TODO: Update remote contact
      return $contact;
    }
  }

  /**
   * @inheritdoc
   */
  public function get($handle) {
    $this->authenticationClient->authenticatePartner();

    $result = $this->contactClient->get($handle);

    return $this->convertToContact($result);
  }

  /**
   * @inheritdoc
   */
  public function getList() {
    $this->authenticationClient->authenticatePartner();

    return array_map('convertToListEntry', $this->contactClient->getList());
  }

  /**
   * @param $contact
   *
   * @return ContactListEntry
   */
  private function convertToListEntry($contact) {
    return new ContactListEntry($contact[0], $contact[1], $contact[2]);
  }

  /**
   * Creates a new contact.
   *
   * @param Contact $contact
   *
   * @return Contact
   * @throws \Exception
   */
  private function create(Contact $contact) {
    $errors = $this->validator->validate($contact);

    if (count($errors) > 0) {
      throw new InvalidContactException((string)$errors);
    }

    $this->authenticationClient->authenticatePartner();

    $handle = $this->contactClient->createContact(
      $contact->getCompany(),
      $contact->getFirstname(),
      $contact->getLastname(),
      $contact->getStreet(),
      $contact->getZipCode(),
      $contact->getCity(),
      $contact->getCountry(),
      $contact->getPhone(),
      $contact->getFax(),
      $contact->getMail(),
      $contact->getState(),
      $contact->getTaxId(),
      $contact->getBirthday()
              ->format('c'),
      $contact->getRegisterId()
    );

    $contact->setHandle($handle);

    return $contact;
  }

  /**
   * @param Contact            $needle
   * @param ContactListEntry[] $haystack
   *
   * @return Contact|null
   * @throws InvalidContactException
   */
  private function findContact(Contact $needle, array $haystack) {
    /** @var ContactListEntry[] $possibleMatches */
    $possibleMatches = [];

    foreach ($haystack as $listEntry) {
      if ($listEntry->getFirstName() == $needle->getFirstname()
          && $listEntry->getLastName() == $needle->getLastname()
      ) {
        $possibleMatches[] = $listEntry;
      }
    }

    /** @var Contact[] $matches */
    $matches = [];

    foreach ($possibleMatches as $possibleMatch) {
      $contact = $this->get($possibleMatch->getHandle());

      if ($contact->getMail() == $needle->getMail() && $contact->getZipCode() == $needle->getZipCode()) {
        $matches[] = $contact;
      }
    }

    if (count($matches) > 1) {
      throw new InvalidContactException("Too many matching contacts");
    } elseif (count($matches) == 0) {
      return null;
    } else {
      return $matches[0];
    }
  }

  /**
   * Converts a result from API to array of Contacts
   *
   * @param $result
   *
   * @return Contact
   */
  private function convertToContact($result) {
    $contact = new Contact();
    $contact->setHandle($result['handle']);

    $contact->setCompany($result['company']);
    $contact->setFirstname($result['firstname']);
    $contact->setLastname($result['lastname']);

    list($street, $houseNumber) = $this->convertAddress($result['address']);
    $contact->setStreet($street);
    $contact->setHouseNumber($houseNumber);

    $contact->setZipCode($result['pcode']);
    $contact->setCity($result['city']);
    $contact->setState($result['state']);
    $contact->setBirthday(null);
    $contact->setCountry($result['country']);
    $contact->setPhone($result['telefon']);
    $contact->setFax($result['fax']);
    $contact->setMail($result['email']);

    return $contact;
  }

  /**
   * Converts a address-line to street and houseNumber
   *
   * @param $address
   *
   * @return array
   */
  private function convertAddress($address) {
    preg_match('/([^\d]+)\s(.+)/i', $address, $result);

    $street = trim($result[1]) ?: null;
    $houseNumber = trim($result[2]) ?: null;

    return [$street, $houseNumber];
  }

}