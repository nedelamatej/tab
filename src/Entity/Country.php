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

use Doctrine\ORM\Mapping as ORM;

use App\Repository\CountryRepository;

/**
 * @brief Country entity class
 */
#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null; ///< country id

  #[ORM\Column(length: 63)]
  private ?string $name = null; ///< country name

  #[ORM\Column(length: 3)]
  private ?string $code = null; ///< country 3-letter code

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
}
