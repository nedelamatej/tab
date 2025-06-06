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
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Repository\PitchRepository;

/**
 * @brief Pitch entity class
 */
#[ORM\Entity(repositoryClass: PitchRepository::class)]
class Pitch {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[OA\Property(description: 'Pitch ID', example: 1, readOnly: true)]
  #[Groups(['pitch:set'])]
  private ?int $id = null; ///< pitch id

  #[ORM\Column(type: Types::DATE_MUTABLE)]
  #[OA\Property(description: 'Pitch date', example: '29.07.2023', type: 'string', format: 'DD.MM.YYYY')]
  #[Groups(['pitch:set'])]
  private ?\DateTimeInterface $date = null; ///< pitch date

  #[ORM\Column(type: Types::TIME_MUTABLE)]
  #[OA\Property(description: 'Pitch date', example: '16:56', type: 'string', format: 'HH:MM')]
  #[Groups(['pitch:set'])]
  private ?\DateTimeInterface $time = null; ///< pitch time

  #[ORM\ManyToOne(inversedBy: 'pitches')]
  #[OA\Property(description: 'Pitch event ID', example: 2, type: 'integer', minimum: 1)]
  #[Groups(['pitch:set'])]
  private ?Event $event = null; ///< pitch event

  #[ORM\ManyToOne(inversedBy: 'pitches')]
  #[OA\Property(description: 'Pitch pitcher ID', example: 6, type: 'integer', minimum: 1)]
  #[Groups(['pitch:set'])]
  private ?Pitcher $pitcher = null; ///< pitch pitcher

  #[ORM\ManyToOne(inversedBy: 'pitches')]
  #[OA\Property(description: 'Pitch type ID', example: null, type: ['integer', 'null'], minimum: 1, nullable: true)]
  #[Groups(['pitch:doc'])]
  private ?Type $type = null; ///< pitch type

  #[ORM\Column]
  #[OA\Property(description: 'Pitch flight duration [s]', example: 0.318, type: 'number', format: 'float')]
  #[Groups(['pitch:doc'])]
  private ?float $t = null; ///< pitch flight duration [s]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch rotation angle [rad]', example: 10.721, type: 'number', format: 'float')]
  #[Groups(['pitch:doc'])]
  private ?float $alpha = null; ///< pitch rotation angle [rad]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch angular velocity [rad/s]', example: 135.396, type: 'number', format: 'float')]
  #[Groups(['pitch:doc'])]
  private ?float $omega = null; ///< pitch angular velocity [rad/s]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch initial x coordinate [m]', example: 11.328, type: 'number', format: 'float')]
  #[Groups(['pitch:set'])]
  private ?float $x_0 = null; ///< pitch initial x coordinate [m]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch initial y coordinate [m]', example: 0.599, type: 'number', format: 'float')]
  #[Groups(['pitch:set'])]
  private ?float $y_0 = null; ///< pitch initial y coordinate [m]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch initial z coordinate [m]', example: -0.058, type: 'number', format: 'float')]
  #[Groups(['pitch:set'])]
  private ?float $z_0 = null; ///< pitch initial z coordinate [m]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch initial translational velocity [m/s]', example: 36.018, type: 'number', format: 'float')]
  #[Groups(['pitch:doc'])]
  private ?float $v_0 = null; ///< pitch initial translational velocity [m/s]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch initial horizontal angle [rad]', example: 1.539, type: 'number', format: 'float')]
  #[Groups(['pitch:doc'])]
  private ?float $phi_0 = null; ///< pitch initial horizontal angle [rad]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch initial vertical angle [rad]', example: 3.027, type: 'number', format: 'float')]
  #[Groups(['pitch:doc'])]
  private ?float $theta_0 = null; ///< pitch initial vertical angle [rad]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch final x coordinate [m]', example: 0.432, type: 'number', format: 'float')]
  #[Groups(['pitch:set'])]
  private ?float $x_t = null; ///< pitch final x coordinate [m]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch final y coordinate [m]', example: 1.040, type: 'number', format: 'float')]
  #[Groups(['pitch:set'])]
  private ?float $y_t = null; ///< pitch final y coordinate [m]

  #[ORM\Column]
  #[OA\Property(description: 'Pitch final z coordinate [m]', example: 0.192, type: 'number', format: 'float')]
  #[Groups(['pitch:set'])]
  private ?float $z_t = null; ///< pitch final z coordinate [m]

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch final translational velocity [m/s]', example: 32.827, type: ['number', 'null'], format: 'float', nullable: true)]
  #[Groups(['pitch:doc'])]
  private ?float $v_t = null; ///< pitch final translational velocity [m/s] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch final horizontal angle [rad]', example: 1.556, type: ['number', 'null'], format: 'float', nullable: true)]
  #[Groups(['pitch:doc'])]
  private ?float $phi_t = null; ///< pitch final horizontal angle [rad] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch final vertical angle [rad]', example: -0.037, type: ['number', 'null'], format: 'float', nullable: true)]
  #[Groups(['pitch:doc'])]
  private ?float $theta_t = null; ///< pitch final vertical angle [rad] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch 1st control point x coordinate [m]', example: 7.537, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:set'])]
  private ?float $x_1 = null; ///< pitch 1st control point x coordinate [m] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch 1st control point y coordinate [m]', example: 1.036, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:set'])]
  private ?float $y_1 = null; ///< pitch 1st control point y coordinate [m] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch 1st control point z coordinate [m]', example: 0.063, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:set'])]
  private ?float $z_1 = null; ///< pitch 1st control point z coordinate [m] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch 2nd control point x coordinate [m]', example: 3.915, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:set'])]
  private ?float $x_2 = null; ///< pitch 2nd control point x coordinate [m] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch 2nd control point y coordinate [m]', example: 1.166, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:set'])]
  private ?float $y_2 = null; ///< pitch 2nd control point y coordinate [m] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch 2nd control point z coordinate [m]', example: 0.143, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:set'])]
  private ?float $z_2 = null; ///< pitch 2nd control point z coordinate [m] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch 3rd control point x coordinate [m]', example: 0.434, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:set'])]
  private ?float $x_3 = null; ///< pitch 3rd control point x coordinate [m] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch 3rd control point y coordinate [m]', example: 1.033, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:set'])]
  private ?float $y_3 = null; ///< pitch 3rd control point y coordinate [m] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch 3rd control point z coordinate [m]', example: 0.193, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:set'])]
  private ?float $z_3 = null; ///< pitch 3rd control point z coordinate [m] (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch drag coefficient', example: 0.330, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:doc'])]
  private ?float $c_d = null; ///< pitch drag coefficient (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch lift coefficient', example: 0.250, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:doc'])]
  private ?float $c_l = null; ///< pitch lift coefficient (optional)

  #[ORM\Column(nullable: true)]
  #[OA\Property(description: 'Pitch computational deviation [m]', example: 0.007, type: ['number', 'null'], format: 'float', readOnly: true, nullable: true)]
  #[Groups(['pitch:doc'])]
  private ?float $delta = null; ///< pitch computational deviation [m] (optional)

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
   * @brief Gets the pitch event
   *
   * @return Event|null event
   */
  public function getEvent(): ?Event {
    return $this->event;
  }

  /**
   * @brief Sets the pitch event
   *
   * @param Event|null $event event
   *
   * @return static self reference
   */
  public function setEvent(?Event $event): static {
    $this->event = $event;

    return $this;
  }

  /**
   * @brief Gets the pitch pitcher
   *
   * @return Pitcher|null pitcher
   */
  public function getPitcher(): ?Pitcher {
    return $this->pitcher;
  }


  /**
   * @brief Sets the pitch pitcher
   *
   * @param Pitcher|null $pitcher pitcher
   *
   * @return static self reference
   */
  public function setPitcher(?Pitcher $pitcher): static {
    $this->pitcher = $pitcher;

    return $this;
  }

  /**
   * @brief Gets the pitch type
   *
   * @return Type|null type
   */
  public function getType(): ?Type {
    return $this->type;
  }

  /**
   * @brief Sets the pitch type
   *
   * @param Type|null $type type
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
   * @brief Gets the pitch initial coordinates
   *
   * @return array initial coordinates [m]
   */
  public function getXyz_0(): array {
    return [$this->x_0, $this->y_0, $this->z_0];
  }

  /**
   * @brief Sets the pitch initial coordinates
   *
   * @param array $xyz_0 initial coordinates [m]
   *
   * @return static self reference
   */
  public function setXyz_0(array $xyz_0): static {
    $this->x_0 = $xyz_0[0];
    $this->y_0 = $xyz_0[1];
    $this->z_0 = $xyz_0[2];

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
   * @brief Gets the pitch initial translational velocity in xyz coordinates
   *
   * @return array initial translational velocity in xyz coordinates [m/s]
   */
  public function getV_xyz_0(): array {
    return [
      $this->v_0 * sin($this->phi_0) * cos($this->theta_0),
      $this->v_0 * sin($this->phi_0) * sin($this->theta_0),
      $this->v_0 * cos($this->phi_0),
    ];
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
   * @brief Gets the pitch final coordinates
   *
   * @return array final coordinates [m]
   */
  public function getXyz_t(): array {
    return [$this->x_t, $this->y_t, $this->z_t];
  }

  /**
   * @brief Sets the pitch final coordinates
   *
   * @param array $xyz_t final coordinates [m]
   *
   * @return static self reference
   */
  public function setXyz_t(array $xyz_t): static {
    $this->x_t = $xyz_t[0];
    $this->y_t = $xyz_t[1];
    $this->z_t = $xyz_t[2];

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
   * @param float|null $v_t final translational velocity [m/s]
   *
   * @return static self reference
   */
  public function setV_t(?float $v_t): static {
    $this->v_t = $v_t;

    return $this;
  }

  /**
   * @brief Gets the pitch final translational velocity in xyz coordinates
   *
   * @return array final translational velocity in xyz coordinates [m/s]
   */
  public function getV_xyz_t(): array {
    return [
      $this->v_t * sin($this->phi_t) * cos($this->theta_t),
      $this->v_t * sin($this->phi_t) * sin($this->theta_t),
      $this->v_t * cos($this->phi_t),
    ];
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
   * @param float|null $phi_t final horizontal angle [rad]
   *
   * @return static self reference
   */
  public function setPhi_t(?float $phi_t): static {
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
   * @param float|null $theta_t final vertical angle [rad]
   *
   * @return static self reference
   */
  public function setTheta_t(?float $theta_t): static {
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
  public function setX_1(?float $x_1): static {
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
  public function setY_1(?float $y_1): static {
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

  /**
   * @brief Sets the pitch 1st control point z coordinate
   *
   * @param float|null $z_1 1st control point z coordinate [m]
   *
   * @return static self reference
   */
  public function setZ_1(?float $z_1): static {
    $this->z_1 = $z_1;

    return $this;
  }

  /**
   * @brief Gets the pitch 1st control point coordinates
   *
   * @return array 1st control point coordinates [m]
   */
  public function getXyz_1(): array {
    return [$this->x_1, $this->y_1, $this->z_1];
  }

  /**
   * @brief Sets the pitch 1st control point coordinates
   *
   * @param array $xyz_1 1st control point coordinates [m]
   *
   * @return static self reference
   */
  public function setXyz_1(array $xyz_1): static {
    $this->x_1 = $xyz_1[0];
    $this->y_1 = $xyz_1[1];
    $this->z_1 = $xyz_1[2];

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
  public function setX_2(?float $x_2): static {
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
  public function setY_2(?float $y_2): static {
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
  public function setZ_2(?float $z_2): static {
    $this->z_2 = $z_2;

    return $this;
  }

  /**
   * @brief Gets the pitch 2nd control point coordinates
   *
   * @return array 2nd control point coordinates [m]
   */
  public function getXyz_2(): array {
    return [$this->x_2, $this->y_2, $this->z_2];
  }

  /**
   * @brief Sets the pitch 2nd control point coordinates
   *
   * @param array $xyz_2 2nd control point coordinates [m]
   *
   * @return static self reference
   */
  public function setXyz_2(array $xyz_2): static {
    $this->x_2 = $xyz_2[0];
    $this->y_2 = $xyz_2[1];
    $this->z_2 = $xyz_2[2];

    return $this;
  }

  /**
   * @brief Gets the pitch 3rd control point x coordinate
   *
   * @return float|null 3rd control point x coordinate [m]
   */
  public function getX_3(): ?float {
    return $this->x_3;
  }

  /**
   * @brief Sets the pitch 3rd control point x coordinate
   *
   * @param float|null $x_3 3rd control point x coordinate [m]
   *
   * @return static self reference
   */
  public function setX_3(?float $x_3): static {
    $this->x_3 = $x_3;

    return $this;
  }

  /**
   * @brief Gets the pitch 3rd control point y coordinate
   *
   * @return float|null 3rd control point y coordinate [m]
   */
  public function getY_3(): ?float {
    return $this->y_3;
  }

  /**
   * @brief Sets the pitch 3rd control point y coordinate
   *
   * @param float|null $y_3 3rd control point y coordinate [m]
   *
   * @return static self reference
   */
  public function setY_3(?float $y_3): static {
    $this->y_3 = $y_3;

    return $this;
  }

  /**
   * @brief Gets the pitch 3rd control point z coordinate
   *
   * @return float|null 3rd control point z coordinate [m]
   */
  public function getZ_3(): ?float {
    return $this->z_3;
  }

  /**
   * @brief Sets the pitch 3rd control point z coordinate
   *
   * @param float|null $z_3 3rd control point z coordinate [m]
   *
   * @return static self reference
   */
  public function setZ_3(?float $z_3): static {
    $this->z_3 = $z_3;

    return $this;
  }

  /**
   * @brief Gets the pitch 3rd control point coordinates
   *
   * @return array 3rd control point coordinates [m]
   */
  public function getXyz_3(): array {
    return [$this->x_3, $this->y_3, $this->z_3];
  }

  /**
   * @brief Sets the pitch 3rd control point coordinates
   *
   * @param array $xyz_3 3rd control point coordinates [m]
   *
   * @return static self reference
   */
  public function setXyz_3(array $xyz_3): static {
    $this->x_3 = $xyz_3[0];
    $this->y_3 = $xyz_3[1];
    $this->z_3 = $xyz_3[2];

    return $this;
  }

  /**
   * @brief Gets the pitch drag coefficient
   *
   * @return float|null drag coefficient
   */
  public function getC_d(): ?float {
    return $this->c_d;
  }

  /**
   * @brief Sets the pitch drag coefficient
   *
   * @param float|null $c_d drag coefficient
   *
   * @return static self reference
   */
  public function setC_d(?float $c_d): static {
    $this->c_d = $c_d;

    return $this;
  }

  /**
   * @brief Gets the pitch lift coefficient
   *
   * @return float|null lift coefficient
   */
  public function getC_l(): ?float {
    return $this->c_l;
  }

  /**
   * @brief Sets the pitch lift coefficient
   *
   * @param float|null $c_l lift coefficient
   *
   * @return static self reference
   */
  public function setC_l(?float $c_l): static {
    $this->c_l = $c_l;

    return $this;
  }

  /**
   * @brief Gets the pitch computational deviation
   *
   * @return float|null computational deviation
   */
  public function getDelta(): ?float {
    return $this->delta;
  }

  /**
   * @brief Sets the pitch computational deviation
   *
   * @param float|null $delta computational deviation
   *
   * @return static self reference
   */
  public function setDelta(?float $delta): static {
    $this->delta = $delta;

    return $this;
  }
}
