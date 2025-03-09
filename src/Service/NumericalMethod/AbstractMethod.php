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
 * @brief AbstractMethod abstract class
 *
 * @author Matej Nedela
 */

namespace App\Service\NumericalMethod;

/**
 * AbstractMethod abstract class
 */
abstract class AbstractMethod {
  protected int $n;   ///< number of steps
  protected float $h; ///< step size

  /**
   * @brief Constructs new AbstractMethod object
   *
   * @param int $n number of steps
   * @param float $t total time
   */
  public function __construct(int $n, float $t) {
    $this->n = $n;
    $this->h = $t / $n;
  }

  /**
   * @brief Performs a single step of the numerical method
   *
   * @param array $state current state
   * @param callable $deriv differential equations derivatives function
   *
   * @return array new state
   */
  abstract protected function step(array $state, callable $deriv): array;

  /**
   * @brief Solves the differential equations using the numerical method
   *
   * @param array $state initial state
   * @param callable $deriv differential equations derivatives function
   *
   * @return array solution
   */
  public function solve(array $state, callable $deriv): array {
    $array = [$state];

    for ($i = 0; $i < $this->n; ++$i) {
      $array[] = $state = $this->step($state, $deriv);
    }

    return $array;
  }
}
