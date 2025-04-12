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
 * @brief Country controller class
 *
 * @author Matej Nedela
 */

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Country;

/**
 * @brief Country controller class
 */
#[Route('/country')]
final class CountryController extends AbstractController {
  private $entityManager; ///< entity manager
  private $validator;     ///< validator

  /**
   * @brief Constructs new CountryController object
   *
   * @param EntityManagerInterface $entityManager entity manager
   * @param ValidatorInterface $validator validator
   */
  public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator) {
    $this->entityManager = $entityManager;
    $this->validator = $validator;
  }

  /**
   * @brief Gets all countries
   *
   * @return JsonResponse countries
   */
  #[Route(['/', '/get'], methods: ['GET'])]
  public function countryGet(): JsonResponse {
    $countries = $this->entityManager->getRepository(Country::class)->findAll();

    return new JsonResponse(array_map(function ($country) {
      return [
        'id' => $country->getId(),
        'name' => $country->getName(),
        'code' => $country->getCode(),
      ];
    }, $countries));
  }

  /**
   * @brief Gets one country by id
   *
   * @param int $id country id
   *
   * @return JsonResponse country
   */
  #[Route(['/{id}', '/get/{id}'], methods: ['GET'])]
  public function countryGetId(int $id): JsonResponse {
    $country = $this->entityManager->getRepository(Country::class)->find($id);
    if (!$country) return new Response(null, 404);

    return new JsonResponse([
      'id' => $country->getId(),
      'name' => $country->getName(),
      'code' => $country->getCode(),
    ]);
  }

  /**
   * @brief Adds new country
   *
   * @param Request $request request
   *
   * @return Response country id
   */
  #[Route(['/', '/add'], methods: ['POST'])]
  public function countryAdd(Request $request): Response {
    $data = json_decode($request->getContent());

    $country = new Country();

    if (property_exists($data, 'name')) $country->setName($data->name);
    if (property_exists($data, 'code')) $country->setCode($data->code);

    $errors = $this->validator->validate($country);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->persist($country);
    $this->entityManager->flush();

    return new Response($country->getId());
  }

  /**
   * @brief Edits one country by id
   *
   * @param int $id country id
   * @param Request $request request
   *
   * @return Response country id
   */
  #[Route(['/{id}', '/edit/{id}'], methods: ['PUT'])]
  public function countryEditId(int $id, Request $request): Response {
    $data = json_decode($request->getContent());

    $country = $this->entityManager->getRepository(Country::class)->find($id);
    if (!$country) return new Response(null, 404);

    if (property_exists($data, 'name')) $country->setName($data->name);
    if (property_exists($data, 'code')) $country->setCode($data->code);

    $errors = $this->validator->validate($country);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->flush();

    return new Response($country->getId());
  }

  /**
   * @brief Deletes one country by id
   *
   * @param int $id country id
   *
   * @return Response country id
   */
  #[Route(['/{id}', '/delete/{id}'], methods: ['DELETE'])]
  public function countryDeleteId(int $id): Response {
    $country = $this->entityManager->getRepository(Country::class)->find($id);
    if (!$country) return new Response(null, 404);

    $this->entityManager->remove($country);
    $this->entityManager->flush();

    return new Response($country->getId());
  }
}
