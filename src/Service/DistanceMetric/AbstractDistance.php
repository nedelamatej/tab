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
 * @brief Abstract distance metric class
 *
 * @author Matej Nedela
 */

namespace App\Service\DistanceMetric;

/**
 * @brief Abstract distance metric class
 */
abstract class AbstractDistance {
  /**
   * @brief Calculates the distance metric between the two points
   *
   * @note The points must have the same dimension
   *
   * @param array $pointA point A
   * @param array $pointB point B
   *
   * @return array distance
   */
  abstract public static function calc(array $pointA, array $pointB): float;
}
