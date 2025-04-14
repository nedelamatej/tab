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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
  private ?Organization $organization = null; ///< event organization

  #[ORM\Column(length: 63)]
  private ?string $name = null; ///< event name

  #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $date = null; ///< event date of occurrence (optional)

  #[ORM\Column(length: 63, nullable: true)]
  private ?string $city = null; ///< event city of occurrence (optional)

  #[ORM\ManyToOne(inversedBy: 'events')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Country $country = null; ///< event country of occurrence

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $details = null; ///< event details (optional)

  /**
   * @var Collection<int, Pitch>
   */
  #[ORM\OneToMany(targetEntity: Pitch::class, mappedBy: 'event')]
  private Collection $pitches; ///< pitches with this event

  /**
   * @brief Constructs new Event object
   */
  public function __construct() {
    $this->pitches = new ArrayCollection();
  }

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
   * @param Organization|null $organization organization
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
   * @brief Gets the event date of occurrence
   *
   * @return \DateTimeInterface|null date
   */
  public function getDate(): ?\DateTimeInterface {
    return $this->date;
  }

  /**
   * @brief Sets the event date of occurrence
   *
   * @param \DateTimeInterface|null $date date
   *
   * @return static self reference
   */
  public function setDate(?\DateTimeInterface $date): static {
    $this->date = $date;

    return $this;
  }

  /**
   * @brief Gets the event city of occurrence
   *
   * @return string|null city
   */
  public function getCity(): ?string {
    return $this->city;
  }

  /**
   * @brief Sets the event city of occurrence
   *
   * @param string|null $city city
   *
   * @return static self reference
   */
  public function setCity(?string $city): static {
    $this->city = $city;

    return $this;
  }

  /**
   * @brief Gets the event country of occurrence
   *
   * @return Country|null country
   */
  public function getCountry(): ?Country {
    return $this->country;
  }

  /**
   * @brief Sets the event country of occurrence
   *
   * @param Country|null $country country
   *
   * @return static self reference
   */
  public function setCountry(?Country $country): static {
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
   * @param string|null $details details
   *
   * @return static self reference
   */
  public function setDetails(?string $details): static {
    $this->details = $details;

    return $this;
  }

  /**
   * @brief Gets the pitches with this event
   *
   * @return Collection<int, Pitch> pitches
   */
  public function getPitches(): Collection {
    return $this->pitches;
  }

  /**
   * @brief Adds a pitch to this event
   *
   * @param Pitch $pitch pitch to add
   *
   * @return static self reference
   */
  public function addPitch(Pitch $pitch): static {
    if (!$this->pitches->contains($pitch)) {
      $this->pitches->add($pitch);
      $pitch->setEvent($this);
    }

    return $this;
  }

  /**
   * @brief Removes a pitch from this event
   *
   * @param Pitch $pitch pitch to remove
   *
   * @return static self reference
   */
  public function removePitch(Pitch $pitch): static {
    if ($this->pitches->removeElement($pitch)) {
      if ($pitch->getEvent() === $this) {
        $pitch->setEvent(null);
      }
    }

    return $this;
  }
}
