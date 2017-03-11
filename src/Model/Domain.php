<?php
/**
 * @author Timo Förster <tfoerster@webfoersterei.de>
 * @date   28.02.17
 */

namespace Hecke29\DomainOffensiveClient\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Domain
 * @package Hecke29\DomainOffensiveClient\Model
 */
class Domain
{

  /**
   * @Assert\Regex("/^[äüöÄÜÖß\w]{2,63}$/")
   * @Assert\Length(min="2", max="63")
   * @Assert\NotBlank()
   * @var string
   */
  private $domain;

  /**
   * @Assert\Regex("/^[a-z]{2,63}(?:\.[a-z]{2,4})?$/")
   * @Assert\Length(min="2", max="63")
   * @Assert\NotBlank()
   * @var string
   */
  private $tld;

  /**
   * @return string
   */
  public function getFullName() {
    return sprintf('%s.%s', $this->domain, $this->tld);
  }

  /**
   * @param string $domain
   *
   * @return Domain
   */
  public function setDomain($domain) {
    $this->domain = $domain;

    return $this;
  }

  /**
   * @param string $tld
   *
   * @return Domain
   */
  public function setTld($tld) {
    $this->tld = $tld;

    return $this;
  }

}