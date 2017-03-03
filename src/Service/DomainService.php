<?php
/**
 * @author Timo Förster <tfoerster@webfoersterei.de>
 * @date   01.03.17
 */

namespace Hecke29\DomainOffensiveClient\Service;

use Hecke29\DomainOffensiveClient\Exception\InvalidContactException;
use Hecke29\DomainOffensiveClient\Exception\InvalidDomainException;
use Hecke29\DomainOffensiveClient\Model\Contact;
use Hecke29\DomainOffensiveClient\Model\Domain;
use Hecke29\DomainOffensiveClient\Service\Client\AuthenticationClient;
use Hecke29\DomainOffensiveClient\Service\Client\DomainClient;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DomainService
 * @package Hecke29\DomainOffensiveClient\Service
 */
class DomainService implements DomainServiceInterface
{
  /**
   * @var AuthenticationClient
   */
  private $authenticationClient;

  /**
   * @var DomainClient
   */
  private $domainClient;

  /**
   * @var ValidatorInterface
   */
  private $validator;

  public function __construct(
    ValidatorInterface $validator,
    AuthenticationClient $authenticationClient,
    DomainClient $domainClient
  ) {
    $this->validator = $validator;
    $this->authenticationClient = $authenticationClient;
    $this->domainClient = $domainClient;
  }

  /**
   * @inheritdoc
   */
  public function register(Domain $domain, Contact $owner, Contact $tech, Contact $admin, Contact $zone) {
    $this->validateContact($owner);
    $this->validateContact($tech);
    $this->validateContact($admin);
    $this->validateContact($zone);

    $domainErrors = $this->validator->validate($domain);
    if ($domainErrors) {
      $firstError = $domainErrors->get(0);
      throw new InvalidDomainException(
        sprintf(
          'Validation failed for domain %s: %s. Value: %s',
          $domain->getFullName(),
          $firstError->getMessage(),
          $firstError->getInvalidValue()
        )
      );
    }

    $this->authenticationClient->authenticatePartner();

    $this->domainClient->create(
      $domain->getFullName(),
      $owner->getHandle(),
      $tech->getHandle(),
      $admin->getHandle(),
      $zone->getHandle(),
      []
    );
  }

  /**
   * Validates that a contact has a handle
   *
   * @param Contact $contact
   *
   * @throws InvalidContactException
   */
  private function validateContact(Contact $contact) {
    if ($this->validator->validate($contact, null, ['handleRequired']) > 0) {
      throw new InvalidContactException('Contact not valid for use with domains.');
    }
  }

}