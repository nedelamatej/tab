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
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
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
  #[Route('/', methods: ['GET'])]
  #[OA\Get(
    summary: 'Country > Get all',
    description: 'Gets all countries.',
    tags: ['Country']
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns all countries.',
    content: new OA\JsonContent(
      type: 'array',
      items: new OA\Items(ref: new Model(type: Country::class))
    )
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
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
  #[Route('/{id}', methods: ['GET'])]
  #[OA\Get(
    summary: 'Country > Get one',
    description: 'Gets one country by id.',
    tags: ['Country']
  )]
  #[OA\PathParameter(
    name: 'id',
    description: 'Country ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns one country.',
    content: new Model(type: Country::class)
  )]
  #[OA\Response(
    response: 404,
    description: 'Country not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function countryGetId(int $id): JsonResponse {
    $country = $this->entityManager->getRepository(Country::class)->find($id);
    if (!$country) return new JsonResponse(null, 404);

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
  #[Route('/', methods: ['POST'])]
  #[OA\Post(
    summary: 'Country > Add new',
    description: 'Adds new country.',
    tags: ['Country']
  )]
  #[OA\RequestBody(
    description: 'Country data',
    content: new Model(type: Country::class),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns added country ID.',
    content: new OA\JsonContent(type: 'integer', exclusiveMinimum: 0)
  )]
  #[OA\Response(
    response: 400,
    description: 'Validation error.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
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
  #[Route('/{id}', methods: ['PUT'])]
  #[OA\Put(
    summary: 'Country > Edit one',
    description: 'Edits one country by id.',
    tags: ['Country']
  )]
  #[OA\PathParameter(
    name: 'id',
    description: 'Country ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\RequestBody(
    description: 'Country data',
    content: new Model(type: Country::class),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns edited country ID.',
    content: new OA\JsonContent(type: 'integer', exclusiveMinimum: 0)
  )]
  #[OA\Response(
    response: 400,
    description: 'Validation error.'
  )]
  #[OA\Response(
    response: 404,
    description: 'Country not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
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
  #[OA\Delete(
    summary: 'Country > Delete one',
    description: 'Deletes one country by id.',
    tags: ['Country']
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns deleted country ID.',
    content: new OA\JsonContent(type: 'integer', exclusiveMinimum: 0)
  )]
  #[OA\Response(
    response: 404,
    description: 'Country not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function countryDeleteId(int $id): Response {
    $country = $this->entityManager->getRepository(Country::class)->find($id);
    if (!$country) return new Response(null, 404);

    $this->entityManager->remove($country);
    $this->entityManager->flush();

    return new Response($country->getId());
  }
}
