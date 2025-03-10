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
 * @brief Abstract differential equations class
 *
 * @author Matej Nedela
 */

namespace App\Service\DifferentialEquations;

/**
 * @brief Abstract differential equations class
 */
abstract class AbstractEquations {
  protected float $c_d;   ///< drag coefficient
  protected float $c_l;   ///< lift coefficient
  protected float $alpha; ///< ball rotation direction angle [rad]
  protected float $m;     ///< ball mass [kg]
  protected float $a;     ///< ball cross sectional area [m^2]
  protected float $g;     ///< gravitational acceleration [m/s^2]
  protected float $rho;   ///< air density [kg/m^3]

  /**
   * @brief Constructs new AbstractEquations object
   *
   * @param float $c_d drag coefficient
   * @param float $c_l lift coefficient
   * @param float $alpha ball rotation direction angle [rad]
   * @param float $m ball mass [kg]
   * @param float $c ball circumference [m]
   * @param float $g gravitational acceleration [m/s^2]
   * @param float $rho air density [kg/m^3]
   */
  public function __construct(float $c_d, float $c_l, float $alpha, float $m, float $c, float $g = 9.813, float $rho = 1.204) {
    $this->c_d = $c_d;
    $this->c_l = $c_l;
    $this->alpha = $alpha;
    $this->m = $m;
    $this->a = ($c ** 2) / (4 * pi());
    $this->g = $g;
    $this->rho = $rho;
  }

  /**
   * @brief Computes the derivatives of the current state
   *
   * \f{eqnarray*}{
   *  x'' &=& \frac{1}{m} \cdot (- \vec{F}_D) \\
   *      &=& \frac{1}{m} \cdot (- \frac{1}{2} \cdot C_D \cdot \rho \cdot A~\cdot x' \cdot \sqrt{x'^2 + y'^2 + z'^2}) \\
   *  y'' &=& \frac{1}{m} \cdot (- \vec{F}_G - \vec{F}_D + \vec{F}_M) \\
   *      &=& \frac{1}{m} \cdot (- m \cdot g - \frac{1}{2} \cdot C_D \cdot \rho \cdot A~\cdot y' \cdot \sqrt{x'^2 + y'^2 + z'^2} + \frac{1}{2} \cdot C_L \cdot \rho \cdot A~\cdot (x'^2 + y'^2 + z'^2) \cdot \sin{\alpha}) \\
   *  z'' &=& \frac{1}{m} \cdot (- \vec{F}_D + \vec{F}_M) \\
   *      &=& \frac{1}{m} \cdot (- \frac{1}{2} \cdot C_D \cdot \rho \cdot A~\cdot z' \cdot \sqrt{x'^2 + y'^2 + z'^2} + \frac{1}{2} \cdot C_L \cdot \rho \cdot A~\cdot (v'^2 + y'^2 + z'^2) \cdot \cos{\alpha})
   * \f}
   *
   * @see Bachelor's Thesis, chapter 3 (_Matematicko-fyzikální popis trajektorie nadhozu_)
   *
   * @param array $state current state
   *
   * @return array derivatives
   */
  public function deriv(array $state): array {
    [$_, $_, $_, $v_x, $v_y, $v_z] = $state;

    $v = sqrt($v_x ** 2 + $v_y ** 2 + $v_z ** 2);

    $a_x = (- 1/2 * $this->c_d * $this->rho * $this->a * $v_x * $v) / $this->m;
    $a_y = (- $this->m * $this->g - 1/2 * $this->c_d * $this->rho * $this->a * $v_y * $v + 1/2 * $this->c_l * $this->rho * $this->a * $v ** 2 * sin($this->alpha)) / $this->m;
    $a_z = (- 1/2 * $this->c_d * $this->rho * $this->a * $v_z * $v + 1/2 * $this->c_l * $this->rho * $this->a * $v ** 2 * cos($this->alpha)) / $this->m;

    return [$v_x, $v_y, $v_z, $a_x, $a_y, $a_z];
  }
}
