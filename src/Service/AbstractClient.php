<?php

namespace Hecke29\DomainOffensiveClient\Service;

abstract class AbstractClient
{
  /**
   * Determines if a response is successful or not
   *
   * @param array $response
   *
   * @return bool
   */
  protected function isSuccessfulResponse(array $response) {
    return $response['result'] === 'success';
  }

  /**
   * Throws exception based on the description message of a failed response
   *
   * @param       $response
   * @param array $exceptionMapping
   * @param       $defaultException
   */
  protected function handleFailedResponse(
    $response,
    array $exceptionMapping = [],
    $defaultException = \Exception::class
  ) {
    $responseDescription = $response['description'];

    foreach ($exceptionMapping as $knownMessage => $exceptionType) {
      if (strpos($responseDescription, $knownMessage) !== false) {
        throw new $exceptionType($responseDescription);
      }
    }

    throw new $defaultException($responseDescription);
  }
}