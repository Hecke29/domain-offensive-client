<?php


namespace Hecke29\DomainOffensiveClient\Service\Client;


use Hecke29\DomainOffensiveClient\Exception\InvalidCredentialsException;
use Hecke29\DomainOffensiveClient\Exception\InvalidPartnerException;
use Hecke29\DomainOffensiveClient\Service\AbstractClient;

class AuthenticationClient extends AbstractClient
{
    /**
     * @var \SoapClient
     */
    private $soapClient;

    /**
     * @var string
     */
    private $partner;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    public function __construct(\SoapClient $soapClient, $partner, $username, $password)
    {
        $this->soapClient = $soapClient;
        $this->partner = $partner;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Authenticates against the SOAP-API.
     *
     * Authentication is IP-based:
     * Once authenticated the user is authorized by it's origin IP until 60 seconds of inactivity.
     * See: https://www.do.de/unterlagen/reseller/soap-api-dokumentation.html#AuthPartner
     *
     * @return bool
     */
    public function authenticatePartner()
    {
        $response = $this->soapClient->__soapCall('AuthPartner', [$this->partner, $this->username, $this->password]);

        if (!$this->isSuccessfulResponse($response)) {
            $this->handleFailedResponse($response, [
                'authentication failed; Please check the PartnerID' => InvalidPartnerException::class,
                'authentication failed; Please check the username and password' => InvalidCredentialsException::class,
            ]);
        }

        return true;
    }

}