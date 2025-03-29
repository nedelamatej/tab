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
 * @brief Bernstein polynomial class
 *
 * @author Matej Nedela
 */

namespace App\Service;

use MathPHP\Probability\Combinatorics;

/**
 * @brief Bernstein polynomial class
 */
final class BernsteinPolynomial {
  /**
   * @brief Calculates the Bernstein polynomial of the degree n
   *
   * @param float $i polynomial index
   * @param float $n polynomial degree
   * @param float $t parameter
   *
   * @return float value
   */
  public static function calc(float $i, float $n, float $t): float {
    return Combinatorics::combinations($n, $i) * $t ** $i * (1 - $t) ** ($n - $i);
  }
}
