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
 * @brief EulerMethod class
 *
 * @author Matej Nedela
 */

namespace App\Service\NumericalMethod;

use App\Service\NumericalMethod\AbstractMethod;

/**
 * EulerMethod class
 */
final class EulerMethod extends AbstractMethod {
  /**
   * @brief Performs a single step of the Euler numerical method
   *
   * @param array $state current state
   * @param callable $deriv differential equations derivatives function
   *
   * @return array new state
   */
  protected function step(array $state, callable $deriv): array {
    $k1 = $deriv($state);

    return array_map(function ($s, $k1) {
      return $s + $k1 * $this->h;
    }, $state, $k1);
  }
}
