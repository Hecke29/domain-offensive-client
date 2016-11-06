<?php


namespace Hecke29\DomainOffensiveClient\Service;


use Hecke29\DomainOffensiveClient\Exception\AuthenticationException;
use Hecke29\DomainOffensiveClient\Exception\InvalidContactException;
use Hecke29\DomainOffensiveClient\Model\Contact;
use Hecke29\DomainOffensiveClient\Service\Client\AuthenticationClient;
use Hecke29\DomainOffensiveClient\Service\Client\ContactClient;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactService
{
    /**
     * @var ContactClient
     */
    private $contactClient;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var AuthenticationClient
     */
    private $authenticationClient;

    public function __construct(ValidatorInterface $validator, AuthenticationClient $authenticationClient, ContactClient $contactClient)
    {
        $this->validator = $validator;
        $this->authenticationClient = $authenticationClient;
        $this->contactClient = $contactClient;
    }

    /**
     * Creates a new contact.
     *
     * @param Contact $contact
     * @return Contact
     * @throws \Exception
     */
    public function create(Contact $contact)
    {
        $errors = $this->validator->validate($contact);

        if (count($errors) > 0) {
            throw new InvalidContactException((string)$errors);
        }

        $this->authenticationClient->authenticatePartner();

        $handle = $this->contactClient->createContact($contact->getCompany(), $contact->getFirstname(),
            $contact->getLastname(), $contact->getStreet(), $contact->getZipCode(), $contact->getCity(),
            $contact->getCountry(), $contact->getPhone(), $contact->getFax(), $contact->getMail(),
            $contact->getState(), $contact->getTaxId(), $contact->getBirthday(), $contact->getRegisterId());

        $contact->setHandle($handle);

        return $contact;
    }

    /**
     * Gets a list of all clients
     *
     * @return array
     * @throws AuthenticationException
     */
    public function getList()
    {
        $this->authenticationClient->authenticatePartner();

        return $this->contactClient->getList();
    }

    /**
     * Gets a single contact by handle.
     *
     * @param $handle
     * @return Contact
     */
    public function get($handle)
    {
        $this->authenticationClient->authenticatePartner();

        $result = $this->contactClient->get($handle);

        $contact = new Contact();
        $contact->setHandle($handle);

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
     * @return array
     */
    private function convertAddress($address)
    {
        preg_match('/([^\d]+)\s(.+)/i', $address, $result);

        $street = trim($result[1]) ?: null;
        $houseNumber = trim($result[2]) ?: null;

        return [$street, $houseNumber];
    }

}