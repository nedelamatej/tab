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
 * @brief Von Neumann neighborhood search class
 *
 * @author Matej Nedela
 */

namespace App\Service\NeighborhoodSearch;

use App\Service\NeighborhoodSearch\AbstractNeighborhood;

/**
 * @brief Von Neumann neighborhood search class
 */
final class VonNeumannNeighborhood extends AbstractNeighborhood {
  /**
   * @brief Constructs new VonNeumannNeighborhood object
   *
   * @param float $xMin x coordinate minimum
   * @param float $yMin y coordinate minimum
   * @param float $xMax x coordinate maximum
   * @param float $yMax y coordinate maximum
   * @param float $xStep x coordinate step size
   * @param float $yStep y coordinate step size
   */
  public function __construct(float $xMin, float $yMin, float $xMax, float $yMax, float $xStep = 0.01, float $yStep = 0.01) {
    $dirs = [[-1, 0], [0, -1], [0, 1], [1, 0]];

    parent::__construct($xMin, $yMin, $xMax, $yMax, $dirs, $xStep, $yStep);
  }
}
