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
 * @brief Country entity class
 *
 * @author Matej Nedela
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Attribute\Ignore as AttributeIgnore;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\CountryRepository;

/**
 * @brief Country entity class
 */
#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[OA\Property(description: 'Country ID', example: 4, readOnly: true)]
  private ?int $id = null; ///< country id

  #[ORM\Column(length: 63)]
  #[OA\Property(description: 'Country name', example: 'Czechia')]
  #[Assert\Length(min: 2, max: 63)]
  private ?string $name = null; ///< country name

  #[ORM\Column(length: 3)]
  #[OA\Property(description: 'Country 3-letter code', example: 'cze')]
  #[Assert\Length(exactly: 3)]
  #[Assert\Regex('/^[a-z]+$/')]
  private ?string $code = null; ///< country 3-letter code

  /**
   * @var Collection<int, Organization>
   */
  #[ORM\OneToMany(targetEntity: Organization::class, mappedBy: 'country')]
  #[AttributeIgnore]
  private Collection $organizations; ///< organizations with this country

  /**
   * @var Collection<int, Event>
   */
  #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'country')]
  #[AttributeIgnore]
  private Collection $events; ///< events with this country

  /**
   * @var Collection<int, Pitcher>
   */
  #[ORM\OneToMany(targetEntity: Pitcher::class, mappedBy: 'country')]
  #[AttributeIgnore]
  private Collection $pitchers; ///< pitchers with this country

  /**
   * @brief Constructs new Country object
   */
  public function __construct() {
    $this->organizations = new ArrayCollection();
    $this->events = new ArrayCollection();
    $this->pitchers = new ArrayCollection();
  }

  /**
   * @brief Gets the country id
   *
   * @return int|null id
   */
  public function getId(): ?int {
    return $this->id;
  }

  /**
   * @brief Gets the country name
   *
   * @return string|null name
   */
  public function getName(): ?string {
    return $this->name;
  }

  /**
   * @brief Sets the country name
   *
   * @param string $name name
   *
   * @return static self reference
   */
  public function setName(string $name): static {
    $this->name = $name;

    return $this;
  }

  /**
   * @brief Gets the country 3-letter code
   *
   * @return string|null code
   */
  public function getCode(): ?string {
    return $this->code;
  }

  /**
   * @brief Sets the country 3-letter code
   *
   * @param string $code code
   *
   * @return static self reference
   */
  public function setCode(string $code): static {
    $this->code = $code;

    return $this;
  }

  /**
   * @brief Gets the organizations with this country
   *
   * @return Collection<int, Organization> organizations
   */
  public function getOrganizations(): Collection {
    return $this->organizations;
  }

  /**
   * @brief Adds an organization to this country
   *
   * @param Organization $organization organization to add
   *
   * @return static self reference
   */
  public function addOrganization(Organization $organization): static {
    if (!$this->organizations->contains($organization)) {
      $this->organizations->add($organization);
      $organization->setCountry($this);
    }

    return $this;
  }

  /**
   * @brief Removes an organization from this country
   *
   * @param Organization $organization organization to remove
   *
   * @return static self reference
   */
  public function removeOrganization(Organization $organization): static {
    if ($this->organizations->removeElement($organization)) {
      if ($organization->getCountry() === $this) {
        $organization->setCountry(null);
      }
    }

    return $this;
  }

  /**
   * @brief Gets the events with this country
   *
   * @return Collection<int, Event> events
   */
  public function getEvents(): Collection {
    return $this->events;
  }

  /**
   * @brief Adds an event to this country
   *
   * @param Event $event event to add
   *
   * @return static self reference
   */
  public function addEvent(Event $event): static {
    if (!$this->events->contains($event)) {
      $this->events->add($event);
      $event->setCountry($this);
    }

    return $this;
  }

  /**
   * @brief Removes an event from this country
   *
   * @param Event $event event to remove
   *
   * @return static self reference
   */
  public function removeEvent(Event $event): static {
    if ($this->events->removeElement($event)) {
      if ($event->getCountry() === $this) {
        $event->setCountry(null);
      }
    }

    return $this;
  }

  /**
   * @brief Gets the pitchers with this country
   *
   * @return Collection<int, Pitcher> pitchers
   */
  public function getPitchers(): Collection {
    return $this->pitchers;
  }

  /**
   * @brief Adds a pitcher to this country
   *
   * @param Pitcher $pitcher pitcher to add
   *
   * @return static self reference
   */
  public function addPitcher(Pitcher $pitcher): static {
    if (!$this->pitchers->contains($pitcher)) {
      $this->pitchers->add($pitcher);
      $pitcher->setCountry($this);
    }

    return $this;
  }

  /**
   * @brief Removes a pitcher from this country
   *
   * @param Pitcher $pitcher pitcher to remove
   *
   * @return static self reference
   */
  public function removePitcher(Pitcher $pitcher): static {
    if ($this->pitchers->removeElement($pitcher)) {
      if ($pitcher->getCountry() === $this) {
        $pitcher->setCountry(null);
      }
    }

    return $this;
  }
}
