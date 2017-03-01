<?php
/**
 * @author Timo Förster <foerster@silpion.de>
 * @date   01.03.17
 */

namespace Hecke29\DomainOffensiveClient\Enum;

/**
 * Class AbstractEnum
 * @package Hecke29\DomainOffensiveClient\Enum
 */
class AbstractEnum
{
  protected static $choices;

  public static function getAll() {
    return static::$choices;
  }

}