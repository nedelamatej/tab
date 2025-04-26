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
 * @brief De Casteljau's algorithm class
 *
 * @author Matej Nedela
 */

namespace App\Service;

/**
 * @brief De Casteljau's algorithm class
 */
final class DeCasteljausAlgorithm {
  /**
   * @brief Calculates the point t on the Bezier curve
   *
   * @param array $points control points
   * @param float $t parameter
   *
   * @return array point
   */
  public static function calc(array $points, float $t): array {
    $n = count($points);

    for ($r = 1; $r < $n; ++$r) {
      for ($j = 0; $j < $n - $r; ++$j) {
        for ($i = 0; $i < count($points[$j]); ++$i) {
          $points[$j][$i] = (1 - $t) * $points[$j][$i] + $t * $points[$j + 1][$i];
        }
      }
    }

    return $points[0];
  }
}
