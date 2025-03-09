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
 * @brief RungeKutta4Method class
 *
 * @author Matej Nedela
 */

namespace App\Service\NumericalMethod;

use App\Service\NumericalMethod\AbstractMethod;

/**
 * RungeKutta4Method class
 */
final class RungeKutta4Method extends AbstractMethod {
  /**
   * @brief Performs a single step of the Runge-Kutta 4th order numerical method
   *
   * @param array $state current state
   * @param callable $deriv differential equations derivatives function
   *
   * @return array new state
   */
  protected function step(array $state, callable $deriv): array {
    $k1 = $deriv($state);
    $k2 = $deriv(array_map(fn ($s, $k1) => $s + $k1 / 2 * $this->h, $state, $k1));
    $k3 = $deriv(array_map(fn ($s, $k2) => $s + $k2 / 2 * $this->h, $state, $k2));
    $k4 = $deriv(array_map(fn ($s, $k3) => $s + $k3 * $this->h, $state, $k3));

    return array_map(function ($s, $k1, $k2, $k3, $k4) {
      return $s + ($k1 + 2 * $k2 + 2 * $k3 + $k4) / 6 * $this->h;
    }, $state, $k1, $k2, $k3, $k4);
  }
}
