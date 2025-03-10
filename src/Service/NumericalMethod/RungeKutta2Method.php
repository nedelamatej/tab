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
 * @brief Runge-Kutta 2nd order numerical method class
 *
 * @author Matej Nedela
 */

namespace App\Service\NumericalMethod;

use App\Service\NumericalMethod\AbstractMethod;

/**
 * @brief Runge-Kutta 2nd order numerical method class
 */
final class RungeKutta2Method extends AbstractMethod {
  /**
   * @brief Performs a single step of the Runge-Kutta 2nd order numerical method
   *
   * @param array $state current state
   * @param callable $deriv differential equations derivatives function
   *
   * @return array new state
   */
  protected function step(array $state, callable $deriv): array {
    $k1 = $deriv($state);
    $k2 = $deriv(array_map(fn ($s, $k1) => $s + $k1 / 2 * $this->h, $state, $k1));

    return array_map(function ($s, $k2) {
      return $s + $k2 * $this->h;
    }, $state, $k2);
  }
}
