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
 * @brief Pitch entity class
 *
 * @author Matej Nedela
 */

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\PitchRepository;

/**
 * @brief Pitch entity class
 */
#[ORM\Entity(repositoryClass: PitchRepository::class)]
class Pitch {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null; ///< pitch id

  #[ORM\Column(type: Types::DATE_MUTABLE)]
  private ?\DateTimeInterface $date = null; ///< pitch date

  #[ORM\Column(type: Types::TIME_MUTABLE)]
  private ?\DateTimeInterface $time = null; ///< pitch time

  #[ORM\ManyToOne(inversedBy: 'pitches')]
  private ?Type $type = null; ///< pitch type

  #[ORM\Column]
  private ?float $t = null; ///< pitch flight duration [s]

  #[ORM\Column]
  private ?float $alpha = null; ///< pitch rotation angle [rad]

  #[ORM\Column]
  private ?float $omega = null; ///< pitch angular velocity [rad/s]

  #[ORM\Column]
  private ?float $x_0 = null; ///< pitch initial x coordinate [m]

  #[ORM\Column]
  private ?float $y_0 = null; ///< pitch initial y coordinate [m]

  #[ORM\Column]
  private ?float $z_0 = null; ///< pitch initial z coordinate [m]

  #[ORM\Column]
  private ?float $v_0 = null; ///< pitch initial translational velocity [m/s]

  #[ORM\Column]
  private ?float $phi_0 = null; ///< pitch initial horizontal angle [rad]

  #[ORM\Column]
  private ?float $theta_0 = null; ///< pitch initial vertical angle [rad]

  #[ORM\Column]
  private ?float $x_t = null; ///< pitch final x coordinate [m]

  #[ORM\Column]
  private ?float $y_t = null; ///< pitch final y coordinate [m]

  #[ORM\Column]
  private ?float $z_t = null; ///< pitch final z coordinate [m]

  #[ORM\Column]
  private ?float $v_t = null; ///< pitch final translational velocity [m/s]

  #[ORM\Column]
  private ?float $phi_t = null; ///< pitch final horizontal angle [rad]

  #[ORM\Column]
  private ?float $theta_t = null; ///< pitch final vertical angle [rad]

  #[ORM\Column]
  private ?float $x_1 = null; ///< pitch 1st control point x coordinate [m]

  #[ORM\Column]
  private ?float $y_1 = null; ///< pitch 1st control point y coordinate [m]

  #[ORM\Column]
  private ?float $z_1 = null; ///< pitch 1st control point z coordinate [m]

  #[ORM\Column]
  private ?float $x_2 = null; ///< pitch 2nd control point x coordinate [m]

  #[ORM\Column]
  private ?float $y_2 = null; ///< pitch 2nd control point y coordinate [m]

  #[ORM\Column]
  private ?float $z_2 = null; ///< pitch 2nd control point z coordinate [m]

  /**
   * @brief Gets the pitch id
   *
   * @return int|null id
   */
  public function getId(): ?int {
    return $this->id;
  }

  /**
   * @brief Gets the pitch date
   *
   * @return \DateTimeInterface|null date
   */
  public function getDate(): ?\DateTimeInterface {
    return $this->date;
  }

  /**
   * @brief Sets the pitch date
   *
   * @param \DateTimeInterface $date date
   *
   * @return static self reference
   */
  public function setDate(\DateTimeInterface $date): static {
    $this->date = $date;

    return $this;
  }

  /**
   * @brief Gets the pitch time
   *
   * @return \DateTimeInterface|null time
   */
  public function getTime(): ?\DateTimeInterface {
    return $this->time;
  }

  /**
   * @brief Sets the pitch time
   *
   * @param \DateTimeInterface $time time
   *
   * @return static self reference
   */
  public function setTime(\DateTimeInterface $time): static {
    $this->time = $time;

    return $this;
  }

  /**
   * @brief Gets the pitch type
   *
   * @return Type|null pitch type
   */
  public function getType(): ?Type {
    return $this->type;
  }

  /**
   * @brief Sets the pitch type
   *
   * @param Type|null $type pitch type
   *
   * @return static self reference
   */
  public function setType(?Type $type): static {
    $this->type = $type;

    return $this;
  }

  /**
   * @brief Gets the pitch flight duration
   *
   * @return float|null flight duration [s]
   */
  public function getT(): ?float {
    return $this->t;
  }

  /**
   * @brief Sets the pitch flight duration
   *
   * @param float $t flight duration [s]
   *
   * @return static self reference
   */
  public function setT(float $t): static {
    $this->t = $t;

    return $this;
  }

  /**
   * @brief Gets the pitch rotation angle
   *
   * @return float|null rotation angle [rad]
   */
  public function getAlpha(): ?float {
    return $this->alpha;
  }

  /**
   * @brief Sets the pitch rotation angle
   *
   * @param float $alpha rotation angle [rad]
   *
   * @return static self reference
   */
  public function setAlpha(float $alpha): static {
    $this->alpha = $alpha;

    return $this;
  }

  /**
   * @brief Gets the pitch angular velocity
   *
   * @return float|null angular velocity [rad/s]
   */
  public function getOmega(): ?float {
    return $this->omega;
  }

  /**
   * @brief Sets the pitch angular velocity
   *
   * @param float $omega angular velocity [rad/s]
   *
   * @return static self reference
   */
  public function setOmega(float $omega): static {
    $this->omega = $omega;

    return $this;
  }

  /**
   * @brief Gets the pitch initial x coordinate
   *
   * @return float|null initial x coordinate [m]
   */
  public function getX_0(): ?float {
    return $this->x_0;
  }

  /**
   * @brief Sets the pitch initial x coordinate
   *
   * @param float $x_0 initial x coordinate [m]
   *
   * @return static self reference
   */
  public function setX_0(float $x_0): static {
    $this->x_0 = $x_0;

    return $this;
  }

  /**
   * @brief Gets the pitch initial y coordinate
   *
   * @return float|null initial y coordinate [m]
   */
  public function getY_0(): ?float {
    return $this->y_0;
  }

  /**
   * @brief Sets the pitch initial y coordinate
   *
   * @param float $y_0 initial y coordinate [m]
   *
   * @return static self reference
   */
  public function setY_0(float $y_0): static {
    $this->y_0 = $y_0;

    return $this;
  }

  /**
   * @brief Gets the pitch initial z coordinate
   *
   * @return float|null initial z coordinate [m]
   */
  public function getZ_0(): ?float {
    return $this->z_0;
  }

  /**
   * @brief Sets the pitch initial z coordinate
   *
   * @param float $z_0 initial z coordinate [m]
   *
   * @return static self reference
   */
  public function setZ_0(float $z_0): static {
    $this->z_0 = $z_0;

    return $this;
  }

  /**
   * @brief Gets the pitch initial translational velocity
   *
   * @return float|null initial translational velocity [m/s]
   */
  public function getV_0(): ?float {
    return $this->v_0;
  }

  /**
   * @brief Sets the pitch initial translational velocity
   *
   * @param float $v_0 initial translational velocity [m/s]
   *
   * @return static self reference
   */
  public function setV_0(float $v_0): static {
    $this->v_0 = $v_0;

    return $this;
  }

  /**
   * @brief Gets the pitch initial horizontal angle
   *
   * @return float|null initial horizontal angle [rad]
   */
  public function getPhi_0(): ?float {
    return $this->phi_0;
  }

  /**
   * @brief Sets the pitch initial horizontal angle
   *
   * @param float $phi_0 initial horizontal angle [rad]
   *
   * @return static self reference
   */
  public function setPhi_0(float $phi_0): static {
    $this->phi_0 = $phi_0;

    return $this;
  }

  /**
   * @brief Gets the pitch initial vertical angle
   *
   * @return float|null initial vertical angle [rad]
   */
  public function getTheta_0(): ?float {
    return $this->theta_0;
  }

  /**
   * @brief Sets the pitch initial vertical angle
   *
   * @param float $theta_0 initial vertical angle [rad]
   *
   * @return static self reference
   */
  public function setTheta_0(float $theta_0): static {
    $this->theta_0 = $theta_0;

    return $this;
  }

  /**
   * @brief Gets the pitch final x coordinate
   *
   * @return float|null final x coordinate [m]
   */
  public function getX_t(): ?float {
    return $this->x_t;
  }

  /**
   * @brief Sets the pitch final x coordinate
   *
   * @param float $x_t final x coordinate [m]
   *
   * @return static self reference
   */
  public function setX_t(float $x_t): static {
    $this->x_t = $x_t;

    return $this;
  }

  /**
   * @brief Gets the pitch final y coordinate
   *
   * @return float|null final y coordinate [m]
   */
  public function getY_t(): ?float {
    return $this->y_t;
  }

  /**
   * @brief Sets the pitch final y coordinate
   *
   * @param float $y_t final y coordinate [m]
   *
   * @return static self reference
   */
  public function setY_t(float $y_t): static {
    $this->y_t = $y_t;

    return $this;
  }

  /**
   * @brief Gets the pitch final z coordinate
   *
   * @return float|null final z coordinate [m]
   */
  public function getZ_t(): ?float {
    return $this->z_t;
  }

  /**
   * @brief Sets the pitch final z coordinate
   *
   * @param float $z_t final z coordinate [m]
   *
   * @return static self reference
   */
  public function setZ_t(float $z_t): static {
    $this->z_t = $z_t;

    return $this;
  }

  /**
   * @brief Gets the pitch final translational velocity
   *
   * @return float|null final translational velocity [m/s]
   */
  public function getV_t(): ?float {
    return $this->v_t;
  }

  /**
   * @brief Sets the pitch final translational velocity
   *
   * @param float $v_t final translational velocity [m/s]
   *
   * @return static self reference
   */
  public function setV_t(float $v_t): static {
    $this->v_t = $v_t;

    return $this;
  }

  /**
   * @brief Gets the pitch final horizontal angle
   *
   * @return float|null final horizontal angle [rad]
   */
  public function getPhi_t(): ?float {
    return $this->phi_t;
  }

  /**
   * @brief Sets the pitch final horizontal angle
   *
   * @param float $phi_t final horizontal angle [rad]
   *
   * @return static self reference
   */
  public function setPhi_t(float $phi_t): static {
    $this->phi_t = $phi_t;

    return $this;
  }

  /**
   * @brief Gets the pitch final vertical angle
   *
   * @return float|null final vertical angle [rad]
   */
  public function getTheta_t(): ?float {
    return $this->theta_t;
  }

  /**
   * @brief Sets the pitch final vertical angle
   *
   * @param float $theta_t final vertical angle [rad]
   *
   * @return static self reference
   */
  public function setTheta_t(float $theta_t): static {
    $this->theta_t = $theta_t;

    return $this;
  }

  /**
   * @brief Gets the pitch 1st control point x coordinate
   *
   * @return float|null 1st control point x coordinate [m]
   */
  public function getX_1(): ?float {
    return $this->x_1;
  }

  /**
   * @brief Sets the pitch 1st control point x coordinate
   *
   * @param float|null $x_1 1st control point x coordinate [m]
   *
   * @return static self reference
   */
  public function setX_1(float $x_1): static {
    $this->x_1 = $x_1;

    return $this;
  }

  /**
   * @brief Gets the pitch 1st control point y coordinate
   *
   * @return float|null 1st control point y coordinate [m]
   */
  public function getY_1(): ?float {
    return $this->y_1;
  }

  /**
   * @brief Sets the pitch 1st control point y coordinate
   *
   * @param float|null $y_1 1st control point y coordinate [m]
   *
   * @return static self reference
   */
  public function setY_1(float $y_1): static {
    $this->y_1 = $y_1;

    return $this;
  }

  /**
   * @brief Gets the pitch 1st control point z coordinate
   *
   * @return float|null 1st control point z coordinate [m]
   */
  public function getZ_1(): ?float {
    return $this->z_1;
  }

  public function setZ_1(float $z_1): static {
    $this->z_1 = $z_1;

    return $this;
  }

  /**
   * @brief Gets the pitch 2nd control point x coordinate
   *
   * @return float|null 2nd control point x coordinate [m]
   */
  public function getX_2(): ?float {
    return $this->x_2;
  }

  /**
   * @brief Sets the pitch 2nd control point x coordinate
   *
   * @param float|null $x_2 2nd control point x coordinate [m]
   *
   * @return static self reference
   */
  public function setX_2(float $x_2): static {
    $this->x_2 = $x_2;

    return $this;
  }

  /**
   * @brief Gets the pitch 2nd control point y coordinate
   *
   * @return float|null 2nd control point y coordinate [m]
   */
  public function getY_2(): ?float {
    return $this->y_2;
  }

  /**
   * @brief Sets the pitch 2nd control point y coordinate
   *
   * @param float|null $y_2 2nd control point y coordinate [m]
   *
   * @return static self reference
   */
  public function setY_2(float $y_2): static {
    $this->y_2 = $y_2;

    return $this;
  }

  /**
   * @brief Gets the pitch 2nd control point z coordinate
   *
   * @return float|null 2nd control point z coordinate [m]
   */
  public function getZ_2(): ?float {
    return $this->z_2;
  }

  /**
   * @brief Sets the pitch 2nd control point z coordinate
   *
   * @param float|null $z_2 2nd control point z coordinate [m]
   *
   * @return static self reference
   */
  public function setZ_2(float $z_2): static {
    $this->z_2 = $z_2;

    return $this;
  }
}
