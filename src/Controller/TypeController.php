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
 * @brief Type controller class
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

use App\Entity\Type;

/**
 * @brief Type controller class
 */
#[Route('/type')]
final class TypeController extends AbstractController {
  private $entityManager; ///< entity manager
  private $validator;     ///< validator

  /**
   * @brief Constructs new TypeController object
   *
   * @param EntityManagerInterface $entityManager entity manager
   * @param ValidatorInterface $validator validator
   */
  public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator) {
    $this->entityManager = $entityManager;
    $this->validator = $validator;
  }

  /**
   * @brief Gets all types
   *
   * @return JsonResponse types
   */
  #[Route('/', methods: ['GET'])]
  #[OA\Get(
    summary: 'Type > Get all',
    description: 'Gets all types.',
    tags: ['Type']
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns all types.',
    content: new OA\JsonContent(
      type: 'array',
      items: new OA\Items(ref: new Model(type: Type::class))
    )
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function typeGet(): JsonResponse {
    $types = $this->entityManager->getRepository(Type::class)->findAll();

    return new JsonResponse(array_map(function ($type) {
      return [
        'id' => $type->getId(),
        'name' => $type->getName(),
        'code' => $type->getCode(),
      ];
    }, $types));
  }

  /**
   * @brief Gets one type by id
   *
   * @param int $id type id
   *
   * @return JsonResponse type
   */
  #[Route('/{id}', methods: ['GET'])]
  #[OA\Get(
    summary: 'Type > Get one',
    description: 'Gets one type by ID.',
    tags: ['Type']
  )]
  #[OA\PathParameter(
    name: 'id',
    description: 'Type ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns one type with given ID.',
    content: new Model(type: Type::class)
  )]
  #[OA\Response(
    response: 404,
    description: 'Type with given ID not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function typeGetId(int $id): JsonResponse {
    $type = $this->entityManager->getRepository(Type::class)->find($id);
    if (!$type) return new JsonResponse(null, 404);

    return new JsonResponse([
      'id' => $type->getId(),
      'name' => $type->getName(),
      'code' => $type->getCode(),
    ]);
  }

  /**
   * @brief Adds new type
   *
   * @param Request $request request
   *
   * @return Response type id
   */
  #[Route('/', methods: ['POST'])]
  public function typeAdd(Request $request): Response {
    $data = json_decode($request->getContent());

    $type = new Type();

    if (property_exists($data, 'name')) $type->setName($data->name);
    if (property_exists($data, 'code')) $type->setCode($data->code);

    $errors = $this->validator->validate($type);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->persist($type);
    $this->entityManager->flush();

    return new Response($type->getId());
  }

  /**
   * @brief Edits one type by id
   *
   * @param int $id type id
   * @param Request $request request
   *
   * @return Response type id
   */
  #[Route('/{id}', methods: ['PUT'])]
  public function typeEditId(int $id, Request $request): Response {
    $data = json_decode($request->getContent());

    $type = $this->entityManager->getRepository(Type::class)->find($id);
    if (!$type) return new Response(null, 404);

    if (property_exists($data, 'name')) $type->setName($data->name);
    if (property_exists($data, 'code')) $type->setCode($data->code);

    $errors = $this->validator->validate($type);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->flush();

    return new Response($type->getId());
  }

  /**
   * @brief Deletes one type by id
   *
   * @param int $id type id
   *
   * @return Response type id
   */
  #[Route('/{id}', methods: ['DELETE'])]
  public function typeDeleteId(int $id): Response {
    $type = $this->entityManager->getRepository(Type::class)->find($id);
    if (!$type) return new Response(null, 404);

    $this->entityManager->remove($type);
    $this->entityManager->flush();

    return new Response($type->getId());
  }
}
