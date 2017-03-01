<?php
/**
 * @author Timo Förster <foerster@silpion.de>
 * @date   01.03.17
 */

namespace Hecke29\DomainOffensiveClient\Enum;

/**
 * Class StateEnum
 * @package Hecke29\DomainOffensiveClient\Enum
 */
class StateEnum extends AbstractEnum
{

  const STATE_BADEN = 'Baden-Württemberg';
  const STATE_BAVARYA = 'Bayern';
  const STATE_BERLIN = 'Berlin';
  const STATE_BRANDENBURG = 'Brandenburg';
  const STATE_BREMEN = 'Bremen';
  const STATE_HAMBURG = 'Hamburg';
  const STATE_HESSE = 'Hessen';
  const STATE_MECK_POMM = 'Mecklenburg-Vorpommern';
  const STATE_LW_SAXONY = 'Niedersachsen';
  const STATE_NORTH_RHINE = 'Nordrhein-Westfalen';
  const STATE_RHINELAND = 'Rheinland-Pfalz';
  const STATE_SAARLAND = 'Saarland';
  const STATE_SAXONY = 'Sachsen';
  const STATE_SAXONY_ANHALT = 'Sachsen-Anhalt';
  const STATE_SCHLESWIG_HOLST = 'Schleswig-Holstein';
  const STATE_THURINGIA = 'Thüringen';

  protected static $choices = [
    self::STATE_BADEN,
    self::STATE_BAVARYA,
    self::STATE_BERLIN,
    self::STATE_BRANDENBURG,
    self::STATE_BREMEN,
    self::STATE_HAMBURG,
    self::STATE_HESSE,
    self::STATE_LW_SAXONY,
    self::STATE_MECK_POMM,
    self::STATE_NORTH_RHINE,
    self::STATE_RHINELAND,
    self::STATE_SAARLAND,
    self::STATE_SAXONY,
    self::STATE_SAXONY_ANHALT,
    self::STATE_SCHLESWIG_HOLST,
    self::STATE_THURINGIA
  ];

}