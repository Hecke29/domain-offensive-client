<?php

namespace Hecke29\DomainOffensiveClient\Model;


use Symfony\Component\Validator\Constraints as Assert;

class Contact
{

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $firstname;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $company;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $street;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $houseNumber;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $zipCode;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $city;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $country;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^\+[0-9]{2}\s[0-9]{2,5}\s[0-9]{3,9}$/")
     * @var string
     */
    private $phone;

    /**
     * @Assert\Regex("/^\+[0-9]{2}\s[0-9]{2,5}\s[0-9]{3,9}$/")
     * @var string
     */
    private $fax;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @var string
     */
    private $mail;

    /**
     * @Assert\NotBlank()
     * TODO: Collection / Enum for Bundesland
     * @var string
     */
    private $state;

    /**
     * USt-IdNr.
     * TODO: RegEx
     * @var string
     */
    private $taxId;

    /**
     * @Assert\Date()
     * @var \DateTime
     */
    private $birthday;

    /**
     * Handelsregisternummer
     * TODO: RegEx
     * @var string
     */
    private $registerId;

    /** @var string */
    private $handle;

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return Contact
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return Contact
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return Contact
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return Contact
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param string $houseNumber
     * @return Contact
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return Contact
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Contact
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Contact
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     * @return Contact
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return Contact
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Contact
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxId()
    {
        return $this->taxId;
    }

    /**
     * @param string $taxId
     * @return Contact
     */
    public function setTaxId($taxId)
    {
        $this->taxId = $taxId;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     * @return Contact
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterId()
    {
        return $this->registerId;
    }

    /**
     * @param string $registerId
     * @return Contact
     */
    public function setRegisterId($registerId)
    {
        $this->registerId = $registerId;
        return $this;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param string $handle
     * @return Contact
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
        return $this;
    }
}