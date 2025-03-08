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
 * @brief Type repository class
 *
 * @author Matej Nedela
 */

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Type;

/**
 * @brief Type repository class
 *
 * @extends ServiceEntityRepository<Type>
 */
final class TypeRepository extends ServiceEntityRepository {
  /**
   * @brief Constructs new TypeRepository object
   *
   * @param ManagerRegistry $registry doctrine registry
   */
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Type::class);
  }
}
