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
 * @brief Event entity class
 *
 * @author Matej Nedela
 */

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\EventRepository;

/**
 * @brief Event entity class
 */
#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null; ///< event id

  #[ORM\ManyToOne(inversedBy: 'events')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Organization $organization = null; ///< event organization (optional)

  #[ORM\Column(length: 63)]
  private ?string $name = null; ///< event name

  #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $date = null; ///< event date of occurrence (optional)

  #[ORM\Column(length: 63, nullable: true)]
  private ?string $city = null; ///< event city of occurrence (optional)

  #[ORM\Column(length: 63, nullable: true)]
  private ?string $country = null; ///< event country of occurrence (optional)

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $details = null; ///< event details (optional)

  /**
   * @brief Gets the event id
   *
   * @return int|null id
   */
  public function getId(): ?int {
    return $this->id;
  }

  /**
   * @brief Gets the event organization
   *
   * @return Organization|null organization
   */
  public function getOrganization(): ?Organization {
    return $this->organization;
  }

  /**
   * @brief Sets the event organization
   *
   * @param Organization|null $organization event organization
   *
   * @return static self reference
   */
  public function setOrganization(?Organization $organization): static {
    $this->organization = $organization;

    return $this;
  }

  /**
   * @brief Gets the event name
   *
   * @return string|null name
   */
  public function getName(): ?string {
    return $this->name;
  }

  /**
   * @brief Sets the event name
   *
   * @param string $name event name
   *
   * @return static self reference
   */
  public function setName(string $name): static {
    $this->name = $name;

    return $this;
  }

  /**
   * @brief Gets the event date
   *
   * @return \DateTimeInterface|null date
   */
  public function getDate(): ?\DateTimeInterface {
    return $this->date;
  }

  /**
   * @brief Sets the event date
   *
   * @param \DateTimeInterface|null $date event date
   *
   * @return static self reference
   */
  public function setDate(?\DateTimeInterface $date): static {
    $this->date = $date;

    return $this;
  }

  /**
   * @brief Gets the event city
   *
   * @return string|null city
   */
  public function getCity(): ?string {
    return $this->city;
  }

  /**
   * @brief Sets the event city
   *
   * @param string|null $city event city
   *
   * @return static self reference
   */
  public function setCity(?string $city): static {
    $this->city = $city;

    return $this;
  }

  /**
   * @brief Gets the event country
   *
   * @return string|null country
   */
  public function getCountry(): ?string {
    return $this->country;
  }

  /**
   * @brief Sets the event country
   *
   * @param string|null $country event country
   *
   * @return static self reference
   */
  public function setCountry(?string $country): static {
    $this->country = $country;

    return $this;
  }

  /**
   * @brief Gets the event details
   *
   * @return string|null details
   */
  public function getDetails(): ?string {
    return $this->details;
  }

  /**
   * @brief Sets the event details
   *
   * @param string|null $details event details
   *
   * @return static self reference
   */
  public function setDetails(?string $details): static {
    $this->details = $details;

    return $this;
  }
}
