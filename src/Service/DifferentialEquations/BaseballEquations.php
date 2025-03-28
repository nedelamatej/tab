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
 * @brief Baseball differential equations class
 *
 * @author Matej Nedela
 */

namespace App\Service\DifferentialEquations;

use App\Service\DifferentialEquations\AbstractEquations;

/**
 * @brief Baseball differential equations class
 */
final class BaseballEquations extends AbstractEquations {
  /**
   * @brief Constructs new BaseballEquations object
   *
   * @param float $c_d drag coefficient
   * @param float $c_l lift coefficient
   * @param float $alpha pitch rotation angle [rad]
   */
  public function __construct(float $c_d, float $c_l, float $alpha) {
    parent::__construct($c_d, $c_l, $alpha, 0.145, 0.232);
  }
}
