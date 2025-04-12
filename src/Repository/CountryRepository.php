<?php
/*
 * Bakalarska prace
 * MODELOVANI A ANALYZA TRAJEKTORII SOFTBALLOVEHO NADHOZU
 *
 * Vysoke uceni technicke v Brne
 * Fakulta informacnich technologii
 * Ustav pocitacove grafiky a multimedii
 *
 * Autor:   Matej Nedela
 * Vedouci: Ing. Tomas Milet, Ph.D.
 */

/**
 * @file
 * @brief Country repository class
 *
 * @author Matej Nedela
 */

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Country;

/**
 * @brief Country repository class
 *
 * @extends ServiceEntityRepository<Country>
 */
final class CountryRepository extends ServiceEntityRepository {
  /**
   * @brief Constructs new CountryRepository object
   *
   * @param ManagerRegistry $registry doctrine registry
   */
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Country::class);
  }
}
