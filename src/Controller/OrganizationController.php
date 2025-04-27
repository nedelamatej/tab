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
 * @brief Organization controller class
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

use App\Entity\Organization;
use App\Entity\Country;

use DateTime;

/**
 * @brief Organization controller class
 */
#[Route('/organization')]
final class OrganizationController extends AbstractController {
  private $entityManager; ///< entity manager
  private $validator;     ///< validator

  /**
   * @brief Constructs new OrganizationController object
   *
   * @param EntityManagerInterface $entityManager entity manager
   * @param ValidatorInterface $validator validator
   */
  public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator) {
    $this->entityManager = $entityManager;
    $this->validator = $validator;
  }

  /**
   * @brief Gets all organizations
   *
   * @return JsonResponse organizations
   */
  #[Route('/', methods: ['GET'])]
  #[OA\Get(
    summary: 'Organization > Get all',
    description: 'Gets all organizations.',
    tags: ['Organization']
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns all organizations.',
    content: new OA\JsonContent(
      type: 'array',
      items: new OA\Items(ref: new Model(type: Organization::class))
    )
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function organizationGet(): JsonResponse {
    $organizations = $this->entityManager->getRepository(Organization::class)->findAll();

    return new JsonResponse(array_map(function ($organization) {
      return [
        'id' => $organization->getId(),
        'name' => $organization->getName(),
        'date' => $organization->getDate()?->format('d.m.Y'),
        'city' => $organization->getCity(),
        'country' => $organization->getCountry()?->getId(),
        'details' => $organization->getDetails(),
      ];
    }, $organizations));
  }

  /**
   * @brief Gets one organization by id
   *
   * @param int $id organization id
   *
   * @return JsonResponse organization
   */
  #[Route('/{id}', methods: ['GET'])]
  #[OA\Get(
    summary: 'Organization > Get one',
    description: 'Gets one organization by ID.',
    tags: ['Organization']
  )]
  #[OA\PathParameter(
    name: 'id',
    description: 'Organization ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns one organization.',
    content: new Model(type: Organization::class)
  )]
  #[OA\Response(
    response: 404,
    description: 'Organization not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function organizationGetId(int $id): JsonResponse {
    $organization = $this->entityManager->getRepository(Organization::class)->find($id);
    if (!$organization) return new JsonResponse(null, 404);

    return new JsonResponse([
      'id' => $organization->getId(),
      'name' => $organization->getName(),
      'date' => $organization->getDate()?->format('d.m.Y'),
      'city' => $organization->getCity(),
      'country' => $organization->getCountry()?->getId(),
      'details' => $organization->getDetails(),
    ]);
  }

  /**
   * @brief Adds new organization
   *
   * @param Request $request request
   *
   * @return Response organization id
   */
  #[Route('/', methods: ['POST'])]
  #[OA\Post(
    summary: 'Organization > Add new',
    description: 'Adds new organization.',
    tags: ['Organization']
  )]
  #[OA\RequestBody(
    description: 'Organization data',
    content: new Model(type: Organization::class),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns added organization ID.',
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
  public function organizationAdd(Request $request): Response {
    $data = json_decode($request->getContent());

    $organization = new Organization();

    if (property_exists($data, 'name')) $organization->setName($data->name);
    if (property_exists($data, 'date')) $organization->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (property_exists($data, 'city')) $organization->setCity($data->city);
    if (property_exists($data, 'country')) $organization->setCountry($data->country ? $this->entityManager->getRepository(Country::class)?->find($data->country) ?? null : null);
    if (property_exists($data, 'details')) $organization->setDetails($data->details);

    $errors = $this->validator->validate($organization);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->persist($organization);
    $this->entityManager->flush();

    return new Response($organization->getId());
  }

  /**
   * @brief Edits one organization by id
   *
   * @param int $id organization id
   * @param Request $request request
   *
   * @return Response organization id
   */
  #[Route('/{id}', methods: ['PUT'])]
  #[OA\Put(
    summary: 'Organization > Edit one',
    description: 'Edits one organization by ID.',
    tags: ['Organization']
  )]
  #[OA\PathParameter(
    name: 'id',
    description: 'Organization ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\RequestBody(
    description: 'Organization data',
    content: new Model(type: Organization::class),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns edited organization ID.',
    content: new OA\JsonContent(type: 'integer', exclusiveMinimum: 0)
  )]
  #[OA\Response(
    response: 400,
    description: 'Validation error.'
  )]
  #[OA\Response(
    response: 404,
    description: 'Organization not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function organizationEditId(int $id, Request $request): Response {
    $data = json_decode($request->getContent());

    $organization = $this->entityManager->getRepository(Organization::class)->find($id);
    if (!$organization) return new Response(null, 404);

    if (property_exists($data, 'name')) $organization->setName($data->name);
    if (property_exists($data, 'date')) $organization->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (property_exists($data, 'city')) $organization->setCity($data->city);
    if (property_exists($data, 'country')) $organization->setCountry($data->country ? $this->entityManager->getRepository(Country::class)?->find($data->country) ?? null : null);
    if (property_exists($data, 'details')) $organization->setDetails($data->details);

    $errors = $this->validator->validate($organization);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->flush();

    return new Response($organization->getId());
  }

  /**
   * @brief Deletes one organization by id
   *
   * @param int $id organization id
   *
   * @return Response organization id
   */
  #[Route('/{id}', methods: ['DELETE'])]
  #[OA\Delete(
    summary: 'Organization > Delete one',
    description: 'Deletes one organization by ID.',
    tags: ['Organization']
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns deleted organization ID.',
    content: new OA\JsonContent(type: 'integer', exclusiveMinimum: 0)
  )]
  #[OA\Response(
    response: 404,
    description: 'Organization not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function organizationDeleteId(int $id): Response {
    $organization = $this->entityManager->getRepository(Organization::class)->find($id);
    if (!$organization) return new Response(null, 404);

    $this->entityManager->remove($organization);
    $this->entityManager->flush();

    return new Response($organization->getId());
  }
}
