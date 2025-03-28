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
use Doctrine\ORM\Mapping as ORM;

use App\Repository\TypeRepository;

/**
 * @brief Type entity class
 */
#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null; ///< type id

  #[ORM\Column(length: 63)]
  private ?string $name = null; ///< type name

  /**
   * @var Collection<int, Pitch>
   */
  #[ORM\OneToMany(targetEntity: Pitch::class, mappedBy: 'type')]
  private Collection $pitches; ///< pitches with this type

  /**
   * @brief Constructs new Type object
   */
  public function __construct() {
    $this->pitches = new ArrayCollection();
  }

  /**
   * @brief Gets the type id
   *
   * @return int|null id
   */
  public function getId(): ?int {
    return $this->id;
  }

  /**
   * @brief Gets the type name
   *
   * @return string|null name
   */
  public function getName(): ?string {
    return $this->name;
  }

  /**
   * @brief Sets the type name
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
   * @brief Gets the pitches with this type
   *
   * @return Collection<int, Pitch> pitches
   */
  public function getPitches(): Collection {
    return $this->pitches;
  }

  /**
   * @brief Adds a pitch to this type
   *
   * @param Pitch $pitch pitch to add
   *
   * @return static self reference
   */
  public function addPitch(Pitch $pitch): static {
    if (!$this->pitches->contains($pitch)) {
      $this->pitches->add($pitch);
      $pitch->setType($this);
    }

    return $this;
  }

  /**
   * @brief Removes a pitch from this type
   *
   * @param Pitch $pitch pitch to remove
   *
   * @return static self reference
   */
  public function removePitch(Pitch $pitch): static {
    if ($this->pitches->removeElement($pitch)) {
      if ($pitch->getType() === $this) {
        $pitch->setType(null);
      }
    }

    return $this;
  }
}
