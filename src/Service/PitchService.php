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
 * @brief Pitch service class
 *
 * @author Matej Nedela
 */

namespace App\Service;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

use App\Entity\Pitch;

use App\Service\BernsteinPolynomial;
use App\Service\DeCasteljausAlgorithm;
use App\Service\DifferentialEquations\SoftballEquations;
use App\Service\DistanceMetric\EuclideanDistance;
use App\Service\NeighborhoodSearch\MooreNeighborhood;
use App\Service\NumericalMethod\RungeKutta4Method;

/**
 * @brief Pitch service class
 */
final class PitchService {
  /**
   * @brief Calculates the pitch trajectory from the initial conditions
   *
   * @param Pitch $pitch pitch
   *
   * @return void
   */
  public static function calc(Pitch $pitch): void {
    $mooreNeighborhood = new MooreNeighborhood(0.2, 0.0, 0.5, 1.0);
    $rungeKutta4Method = new RungeKutta4Method(99, $pitch->getT());

    $state = [...$pitch->getXyz_0(), ...$pitch->getV_xyz_0()];

    [$c_d, $c_l] = $mooreNeighborhood->approx(function ($c_d, $c_l) use ($pitch, $state, $rungeKutta4Method) {
      $softballEquations = new SoftballEquations($c_d, $c_l, $pitch->getAlpha());

      $solution = $rungeKutta4Method->solve($state, [$softballEquations, 'deriv']);

      return EuclideanDistance::calc($pitch->getXyz_t(), end($solution));
    });

    $softballEquations = new SoftballEquations($c_d, $c_l, $pitch->getAlpha());

    $solution = $rungeKutta4Method->solve($state, [$softballEquations, 'deriv']);

    $pitch->setC_d($c_d);
    $pitch->setC_l($c_l);
    $pitch->setDelta(EuclideanDistance::calc($pitch->getXyz_t(), end($solution)));

    $matrix = [];

    for ($j = 0; $j < count($solution); ++$j) {
      for ($i = 0; $i < 4; ++$i) {
        $matrix[$j][$i] = BernsteinPolynomial::calc($i, 3, 1 / (count($solution) - 1) * $j);
      }
    }

    $B = MatrixFactory::createNumeric($matrix);
    $BTBBT = $B->transpose()->multiply($B)->inverse()->multiply($B->transpose());

    $x = $BTBBT->vectorMultiply(new Vector(array_column($solution, 0)));
    $y = $BTBBT->vectorMultiply(new Vector(array_column($solution, 1)));
    $z = $BTBBT->vectorMultiply(new Vector(array_column($solution, 2)));

    $pitch->setXyz_1([$x->get(1), $y->get(1), $z->get(1)]);
    $pitch->setXyz_2([$x->get(2), $y->get(2), $z->get(2)]);
    $pitch->setXyz_3([$x->get(3), $y->get(3), $z->get(3)]);
  }

  /**
   * @brief Compares the first half of the pitch trajectories for pitch tunneling
   *
   * @param Pitch $pitchA first pitch
   * @param Pitch $pitchB second pitch
   *
   * @return float pitch tunneling percentage
   */
  public static function compHalf(Pitch $pitchA, Pitch $pitchB): float {
    $pointsA = [
      array_map(fn ($a, $b) => $a - $b, $pitchA->getXyz_0(), $pitchA->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchA->getXyz_1(), $pitchA->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchA->getXyz_2(), $pitchA->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchA->getXyz_3(), $pitchA->getXyz_0()),
    ];

    $pointsB = [
      array_map(fn ($a, $b) => $a - $b, $pitchB->getXyz_0(), $pitchB->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchB->getXyz_1(), $pitchB->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchB->getXyz_2(), $pitchB->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchB->getXyz_3(), $pitchB->getXyz_0()),
    ];

    $dist = 0;

    for ($i = 0; $i < 50; ++$i) {
      $pointA = DeCasteljausAlgorithm::calc($pointsA, $i / 99);
      $pointB = DeCasteljausAlgorithm::calc($pointsB, $i / 99);

      $dist += EuclideanDistance::calc($pointA, $pointB);
    }

    $dist /= 50;

    return max(0, 1 - $dist / 0.194);
  }

  /**
   * @brief Compares the last point of the pitch trajectories for pitch tunneling
   *
   * @param Pitch $pitchA pitch A
   * @param Pitch $pitchB pitch B
   *
   * @return float pitch tunneling percentage
   */
  public static function compLast(Pitch $pitchA, Pitch $pitchB): float {
    $pointsA = [
      array_map(fn ($a, $b) => $a - $b, $pitchA->getXyz_0(), $pitchA->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchA->getXyz_1(), $pitchA->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchA->getXyz_2(), $pitchA->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchA->getXyz_3(), $pitchA->getXyz_0()),
    ];

    $pointsB = [
      array_map(fn ($a, $b) => $a - $b, $pitchB->getXyz_0(), $pitchB->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchB->getXyz_1(), $pitchB->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchB->getXyz_2(), $pitchB->getXyz_0()),
      array_map(fn ($a, $b) => $a - $b, $pitchB->getXyz_3(), $pitchB->getXyz_0()),
    ];

    $pointA = DeCasteljausAlgorithm::calc($pointsA, 1);
    $pointB = DeCasteljausAlgorithm::calc($pointsB, 1);

    $dist = EuclideanDistance::calc($pointA, $pointB);

    return min(1, $dist / 0.432);
  }

  /**
   * @brief Compares the difference between first points of the pitch trajectories
   *
   * @param Pitch $pitchA pitch A
   * @param Pitch $pitchB pitch B
   *
   * @return float pitch tunneling distance
   */
  public static function compDiff(Pitch $pitchA, Pitch $pitchB): float {
    return EuclideanDistance::calc($pitchA->getXyz_0(), $pitchB->getXyz_0());
  }
}
