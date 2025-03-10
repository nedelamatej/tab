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
 * @brief Softball differential equations class
 *
 * @author Matej Nedela
 */

namespace App\Service\DifferentialEquations;

use App\Service\DifferentialEquations\AbstractEquations;

/**
 * @brief Softball differential equations class
 */
final class SoftballEquations extends AbstractEquations {
  /**
   * @brief Constructs new SoftballEquations object
   *
   * @param float $c_d drag coefficient
   * @param float $c_l lift coefficient
   * @param float $alpha ball rotation direction angle [rad]
   */
  public function __construct(float $c_d, float $c_l, float $alpha) {
    parent::__construct($c_d, $c_l, $alpha, 0.188, 0.305);
  }
}
