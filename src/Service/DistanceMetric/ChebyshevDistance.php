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
 * @brief Chebyshev distance metric class
 *
 * @author Matej Nedela
 */

namespace App\Service\DistanceMetric;

use App\Service\DistanceMetric\AbstractDistance;

/**
 * @brief Chebyshev distance metric class
 */
final class ChebyshevDistance extends AbstractDistance {
  /**
   * @brief Calculates the Chebyshev distance metric between the two points
   *
   * @note The points must have the same dimension
   *
   * @param array $pointA point A
   * @param array $pointB point B
   *
   * @return array distance
   */
  public static function calc(array $pointA, array $pointB): float {
    $distances = [];

    for ($i = 0; $i < count($pointA); ++$i) {
      $distances[] = abs($pointA[$i] - $pointB[$i]);
    }

    return max($distances);
  }
}
