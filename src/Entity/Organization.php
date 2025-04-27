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
 * @brief Type entity class
 *
 * @author Matej Nedela
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Attribute\Ignore as AttributeIgnore;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\OrganizationRepository;

/**
 * @brief Organization entity class
 */
#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[OA\Property(description: 'Organization ID', example: 1, readOnly: true)]
  private ?int $id = null; ///< organization id

  #[ORM\Column(length: 63)]
  #[OA\Property(description: 'Organization name', example: 'Czech Softball Association')]
  #[Assert\Length(min: 2, max: 63)]
  private ?string $name = null; ///< organization name

  #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
  #[OA\Property(description: 'Organization date of foundation', example: '01.01.2014', type: ['string', 'null'], format: 'DD.MM.YYYY')]
  private ?\DateTimeInterface $date = null; ///< organization date of foundation (optional)

  #[ORM\Column(length: 63, nullable: true)]
  #[OA\Property(description: 'Organization city of foundation', example: 'Prague')]
  #[Assert\Length(min: 2, max: 63)]
  private ?string $city = null; ///< organization city of foundation (optional)

  #[ORM\ManyToOne(inversedBy: 'organizations')]
  #[ORM\JoinColumn(nullable: false)]
  #[OA\Property(description: 'Organization country of foundation ID', example: 4, type: 'integer', exclusiveMinimum: 0)]
  private ?Country $country = null; ///< organization country of foundation

  #[ORM\Column(length: 255, nullable: true)]
  #[OA\Property(description: 'Organization details', example: null)]
  #[Assert\Length(min: 2, max: 255)]
  private ?string $details = null; ///< organization details (optional)

  /**
   * @var Collection<int, Event>
   */
  #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'organization')]
  #[AttributeIgnore]
  private Collection $events; ///< events with this organization

  /**
   * @var Collection<int, Pitcher>
   */
  #[ORM\OneToMany(targetEntity: Pitcher::class, mappedBy: 'organization')]
  #[AttributeIgnore]
  private Collection $pitchers; ///< pitchers with this organization

  /**
   * @brief Constructs new Organization object
   */
  public function __construct() {
    $this->events = new ArrayCollection();
    $this->pitchers = new ArrayCollection();
  }

  /**
   * @brief Gets the organization id
   *
   * @return int|null id
   */
  public function getId(): ?int {
    return $this->id;
  }

  /**
   * @brief Gets the organization name
   *
   * @return string|null name
   */
  public function getName(): ?string {
    return $this->name;
  }

  /**
   * @brief Sets the organization name
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
   * @brief Gets the organization date of foundation
   *
   * @return \DateTimeInterface|null date
   */
  public function getDate(): ?\DateTimeInterface {
    return $this->date;
  }

  /**
   * @brief Sets the organization date of foundation
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
   * @brief Gets the organization city of foundation
   *
   * @return string|null city
   */
  public function getCity(): ?string {
    return $this->city;
  }

  /**
   * @brief Sets the organization city of foundation
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
   * @brief Gets the organization country of foundation
   *
   * @return Country|null country
   */
  public function getCountry(): ?Country {
    return $this->country;
  }

  /**
   * @brief Sets the organization country of foundation
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
   * @brief Gets the organization details
   *
   * @return string|null note
   */
  public function getDetails(): ?string {
    return $this->details;
  }

  /**
   * @brief Sets the organization details
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
   * @brief Gets the events with this organization
   *
   * @return Collection<int, Event> events
   */
  public function getEvents(): Collection {
    return $this->events;
  }

  /**
   * @brief Adds an event to this organization
   *
   * @param Event $event event to add
   *
   * @return static self reference
   */
  public function addEvent(Event $event): static {
    if (!$this->events->contains($event)) {
      $this->events->add($event);
      $event->setOrganization($this);
    }

    return $this;
  }

  /**
   * @brief Removes an event from this organization
   *
   * @param Event $event event to remove
   *
   * @return static self reference
   */
  public function removeEvent(Event $event): static {
    if ($this->events->removeElement($event)) {
      if ($event->getOrganization() === $this) {
        $event->setOrganization(null);
      }
    }

    return $this;
  }

  /**
   * @brief Gets the pitchers with this organization
   *
   * @return Collection<int, Pitcher> pitchers
   */
  public function getPitchers(): Collection {
    return $this->pitchers;
  }

  /**
   * @brief Adds a pitcher to this organization
   *
   * @param Pitcher $pitcher pitcher to add
   *
   * @return static self reference
   */
  public function addPitcher(Pitcher $pitcher): static {
    if (!$this->pitchers->contains($pitcher)) {
      $this->pitchers->add($pitcher);
      $pitcher->setOrganization($this);
    }

    return $this;
  }

  /**
   * @brief Removes a pitcher from this organization
   *
   * @param Pitcher $pitcher pitcher to remove
   *
   * @return static self reference
   */
  public function removePitcher(Pitcher $pitcher): static {
    if ($this->pitchers->removeElement($pitcher)) {
      if ($pitcher->getOrganization() === $this) {
        $pitcher->setOrganization(null);
      }
    }

    return $this;
  }
}
