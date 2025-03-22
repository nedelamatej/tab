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
 * @brief Euclidean distance metric class
 *
 * @author Matej Nedela
 */

namespace App\Service\DistanceMetric;

use App\Service\DistanceMetric\AbstractDistance;

/**
 * @brief Euclidean distance metric class
 */
final class EuclideanDistance extends AbstractDistance {
  /**
   * @brief Calculates the Euclidean distance metric between the two points
   *
   * @note The points should have the same dimension
   *
   * @param array $pointA point A
   * @param array $pointB point B
   *
   * @return array distance
   */
  public static function calc(array $pointA, array $pointB): float {
    $distance = 0.0;

    for ($i = 0; $i < count($pointA); ++$i) {
      $distance += ($pointA[$i] - $pointB[$i]) ** 2;
    }

    return sqrt($distance);
  }
}
