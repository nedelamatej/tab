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
 * @brief Pitcher entity class
 *
 * @author Matej Nedela
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\PitcherRepository;

/**
 * @brief Pitcher entity class
 */
#[ORM\Entity(repositoryClass: PitcherRepository::class)]
class Pitcher {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null; ///< pitcher id

  #[ORM\ManyToOne(inversedBy: 'pitchers')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Organization $organization = null; ///< pitcher organization (optional)

  #[ORM\Column(length: 31)]
  private ?string $firstName = null; ///< pitcher first name

  #[ORM\Column(length: 31)]
  private ?string $lastName = null; ///< pitcher last name

  #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $date = null; ///< pitcher date of birth (optional)

  #[ORM\Column(length: 63, nullable: true)]
  private ?string $city = null; ///< pitcher city of birth (optional)

  #[ORM\ManyToOne(inversedBy: 'pitchers')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Country $country = null; ///< pitcher country of birth (optional)

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $details = null; ///< pitcher details (optional)

  /**
   * @var Collection<int, Pitch>
   */
  #[ORM\OneToMany(targetEntity: Pitch::class, mappedBy: 'pitcher')]
  private Collection $pitches; ///< pitches with this pitcher

  /**
   * @brief Constructs new Pitcher object
   */
  public function __construct() {
    $this->pitches = new ArrayCollection();
  }

  /**
   * @brief Gets the pitcher id
   *
   * @return int|null id
   */
  public function getId(): ?int {
    return $this->id;
  }

  /**
   * @brief Gets the pitcher organization
   *
   * @return Organization|null organization
   */
  public function getOrganization(): ?Organization {
    return $this->organization;
  }

  /**
   * @brief Sets the pitcher organization
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
   * @brief Gets the pitcher first name
   *
   * @return string|null first name
   */
  public function getFirstName(): ?string {
    return $this->firstName;
  }

  /**
   * @brief Sets the pitcher first name
   *
   * @param string $firstName first name
   *
   * @return static self reference
   */
  public function setFirstName(string $firstName): static {
    $this->firstName = $firstName;

    return $this;
  }

  /**
   * @brief Gets the pitcher last name
   *
   * @return string|null last name
   */
  public function getLastName(): ?string {
    return $this->lastName;
  }

  /**
   * @brief Sets the pitcher last name
   *
   * @param string $lastName last name
   *
   * @return static self reference
   */
  public function setLastName(string $lastName): static {
    $this->lastName = $lastName;

    return $this;
  }

  /**
   * @brief Gets the pitcher date of birth
   *
   * @return \DateTimeInterface|null date
   */
  public function getDate(): ?\DateTimeInterface {
    return $this->date;
  }

  /**
   * @brief Sets the pitcher date of birth
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
   * @brief Gets the pitcher city of birth
   *
   * @return string|null city
   */
  public function getCity(): ?string {
    return $this->city;
  }

  /**
   * @brief Sets the pitcher city of birth
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
   * @brief Gets the pitcher country of birth
   *
   * @return Country|null country
   */
  public function getCountry(): ?Country {
    return $this->country;
  }

  /**
   * @brief Sets the pitcher country of birth
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
   * @brief Gets the pitcher details
   *
   * @return string|null details
   */
  public function getDetails(): ?string {
    return $this->details;
  }

  /**
   * @brief Sets the pitcher details
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
   * @brief Gets the pitches with this pitcher
   *
   * @return Collection<int, Pitch> pitches
   */
  public function getPitches(): Collection {
    return $this->pitches;
  }

  /**
   * @brief Adds a pitch to this pitcher
   *
   * @param Pitch $pitch pitch to add
   *
   * @return static self reference
   */
  public function addPitch(Pitch $pitch): static {
    if (!$this->pitches->contains($pitch)) {
      $this->pitches->add($pitch);
      $pitch->setPitcher($this);
    }

    return $this;
  }

  /**
   * @brief Removes a pitch from this pitcher
   *
   * @param Pitch $pitch pitch to remove
   *
   * @return static self reference
   */
  public function removePitch(Pitch $pitch): static {
    if ($this->pitches->removeElement($pitch)) {
      if ($pitch->getPitcher() === $this) {
        $pitch->setPitcher(null);
      }
    }

    return $this;
  }
}
