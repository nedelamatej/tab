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
 * @brief Abstract neighborhood search class
 *
 * @author Matej Nedela
 */

namespace App\Service\NeighborhoodSearch;

/**
 * @brief Abstract neighborhood search class
 */
abstract class AbstractNeighborhood {
  protected float $xMin;  ///< x coordinate minimum
  protected float $yMin;  ///< y coordinate minimum
  protected float $xMax;  ///< x coordinate maximum
  protected float $yMax;  ///< y coordinate maximum
  protected array $dirs;  ///< directions
  protected float $xStep; ///< x coordinate step size
  protected float $yStep; ///< y coordinate step size

  /**
   * @brief Constructs new AbstractNeighborhood object
   *
   * @param float $xMin x coordinate minimum
   * @param float $yMin y coordinate minimum
   * @param float $xMax x coordinate maximum
   * @param float $yMax y coordinate maximum
   * @param array $dirs directions
   * @param float $xStep x coordinate step size
   * @param float $yStep y coordinate step size
   */
  public function __construct(float $xMin, float $yMin, float $xMax, float $yMax, array $dirs, float $xStep = 0.01, float $yStep = 0.01) {
    $this->xMin = $xMin;
    $this->yMin = $yMin;
    $this->xMax = $xMax;
    $this->yMax = $yMax;
    $this->dirs = $dirs;
    $this->xStep = $xStep;
    $this->yStep = $yStep;
  }

  /**
   * @brief Approximates the optimal coordinates of the function
   *
   * @param callable $solve function to minimize
   *
   * @return array optimal coordinates
   */
  public function approx(callable $solve): array {
    $x = ($this->xMin + $this->xMax) / 2;
    $y = ($this->yMin + $this->yMax) / 2;

    $min = $solve($x, $y);

    while ($x >= $this->xMin && $x <= $this->xMax && $y >= $this->yMin && $y <= $this->yMax) {
      foreach ($this->dirs as [$dx, $dy]) {
        $val = $solve($x + $dx * $this->xStep, $y + $dy * $this->yStep);

        if ($val < $min) {
          $bestX = $x + $dx * $this->xStep;
          $bestY = $y + $dy * $this->yStep;

          $min = $val;
        }
      }

      if ($x === $bestX && $y === $bestY) {
        break;
      }

      $x = $bestX;
      $y = $bestY;
    }

    return [$x, $y];
  }
}
