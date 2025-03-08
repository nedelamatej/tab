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
}
