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
     * @return array
     * @throws AuthenticationException
     */
    public function getList()
    {
        $this->authenticationClient->authenticatePartner();

        return $this->contactClient->getList();
    }

}