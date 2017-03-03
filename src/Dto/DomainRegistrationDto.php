<?php
/**
 * @author Timo Förster <tfoerster@webfoersterei.de>
 * @date   02.03.17
 */

namespace Hecke29\DomainOffensiveClient\Dto;

use Hecke29\DomainOffensiveClient\Model\Contact;
use Hecke29\DomainOffensiveClient\Model\Domain;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DomainRegistrationDto
 */
class DomainRegistrationDto
{

  /**
   * @Assert\Valid()
   * @Assert\NotNull()
   * @var Contact
   */
  public $adminC;

  /**
   * @Assert\Valid()
   * @Assert\NotNull()
   * @var Domain
   */
  public $domain;

  /**
   * @Assert\Range(min="1", max="1")
   * @var int
   */
  public $duration = 1;

  /**
   * @Assert\All(
   *   @Assert\Ip()
   * )
   * @Assert\Count(min="2")
   * @var array
   */
  public $nameServers;

  /**
   * @Assert\Valid()
   * @Assert\NotNull()
   * @var Contact
   */
  public $ownerC;

  /**
   * @Assert\EqualTo("y")
   * @var string
   */
  public $period = 'y';

  /**
   * @Assert\Valid()
   * @Assert\NotNull()
   * @var Contact
   */
  public $techC;

  /**
   * @Assert\Valid()
   * @Assert\NotNull()
   * @var Contact
   */
  public $zoneC;

}