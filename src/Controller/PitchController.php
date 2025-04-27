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
 * @brief Pitch controller class
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

use App\Entity\Pitch;
use App\Entity\Event;
use App\Entity\Pitcher;
use App\Entity\Type;

use App\Service\PitchService;

use DateTime;

/**
 * @brief Event controller class
 */
#[Route('/pitch')]
final class PitchController extends AbstractController {
  private $entityManager; ///< entity manager
  private $validator;     ///< validator

  /**
   * @brief Constructs new PitchController object
   *
   * @param EntityManagerInterface $entityManager entity manager
   * @param ValidatorInterface $validator validator
   */
  public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator) {
    $this->entityManager = $entityManager;
    $this->validator = $validator;
  }

  /**
   * @brief Gets all pitches
   *
   * @return JsonResponse pitches
   */
  #[Route('/', methods: ['GET'])]
  public function pitchGet(): JsonResponse {
    $pitches = $this->entityManager->getRepository(Pitch::class)->findAll();

    return new JsonResponse(array_map(function ($pitch) {
      return [
        'id' => $pitch->getId(),
        'idx' => $pitch->getId(),
        'cnt' => $this->entityManager->getRepository(Pitch::class)->count([]),
        'date' => $pitch->getDate()?->format('d.m.Y'),
        'time' => $pitch->getTime()?->format('H:i'),
        'event' => [
          'id' => $pitch->getEvent()?->getId(),
          'organization' => $pitch->getEvent()?->getOrganization()?->getId(),
          'name' => $pitch->getEvent()?->getName(),
          'date' => $pitch->getEvent()?->getDate()?->format('d.m.Y'),
          'city' => $pitch->getEvent()?->getCity(),
          'country' => $pitch->getEvent()?->getCountry()?->getId(),
          'details' => $pitch->getEvent()?->getDetails(),
        ],
        'pitcher' => [
          'id' => $pitch->getPitcher()?->getId(),
          'organization' => $pitch->getPitcher()?->getOrganization()?->getId(),
          'firstName' => $pitch->getPitcher()?->getFirstName(),
          'lastName' => $pitch->getPitcher()?->getLastName(),
          'date' => $pitch->getPitcher()?->getDate()?->format('d.m.Y'),
          'city' => $pitch->getPitcher()?->getCity(),
          'country' => $pitch->getPitcher()?->getCountry()?->getId(),
          'details' => $pitch->getPitcher()?->getDetails(),
        ],
        'type' => $pitch->getType()?->getId(),
        't' => $pitch->getT(),
        'alpha' => $pitch->getAlpha(),
        'omega' => $pitch->getOmega(),
        'v_0' => $pitch->getV_0(),
        'phi_0' => $pitch->getPhi_0(),
        'theta_0' => $pitch->getTheta_0(),
        'v_t' => $pitch->getV_t(),
        'phi_t' => $pitch->getPhi_t(),
        'theta_t' => $pitch->getTheta_t(),
        'c_d' => $pitch->getC_d(),
        'c_l' => $pitch->getC_l(),
        'delta' => $pitch->getDelta(),
        'xyz_0' => $pitch->getXyz_0(),
        'xyz_t' => $pitch->getXyz_t(),
        'xyz_1' => $pitch->getXyz_1(),
        'xyz_2' => $pitch->getXyz_2(),
        'xyz_3' => $pitch->getXyz_3(),
      ];
    }, $pitches));
  }

  /**
   * @brief Gets one pitch by id
   *
   * @param int $id pitch id
   *
   * @return JsonResponse pitch
   */
  #[Route('/{id}', methods: ['GET'])]
  #[OA\Get(
    summary: 'Pitch > Get one',
    description: 'Gets one pitch by ID.',
    tags: ['Pitch']
  )]
  #[OA\PathParameter(
    name: 'id',
    description: 'Pitch ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns one pitch with given ID.',
    content: new OA\JsonContent(
      allOf: [
        new OA\Schema(
          properties: [
            new OA\Property(property: 'id', description: 'Pitch ID', example: 1, readOnly: true),
            new OA\Property(property: 'idx', description: 'Pitch index', example: 1, type: 'integer'),
            new OA\Property(property: 'cnt', description: 'Number of pitches (total)', example: 199, type: 'integer'),
            new OA\Property(property: 'date', description: 'Pitch date', example: '29.07.2023', type: 'string', format: 'DD.MM.YYYY'),
            new OA\Property(property: 'time', description: 'Pitch date', example: '16:56', type: 'string', format: 'HH:MM'),
            new OA\Property(property: 'event', description: 'Pitch event', ref: new Model(type: Event::class)),
            new OA\Property(property: 'pitcher', description: 'Pitch pitcher', ref: new Model(type: Pitcher::class))
          ]
        ),
        new OA\Schema(ref: new Model(type: Pitch::class, groups: ['pitch:doc', 'pitch:get'])),
        new OA\Schema(
          properties: [
            new OA\Property(property: 'xyz_0', description: 'Pitch initial x, y and z coordinate [m]', example: [11.328, 0.599, -0.058], type: 'array', items: new OA\Items(type: 'number', format: 'float')),
            new OA\Property(property: 'xyz_t', description: 'Pitch final x, y and z coordinate [m]', example: [0.432, 1.040, 0.192], type: 'array', items: new OA\Items(type: 'number', format: 'float')),
            new OA\Property(property: 'xyz_1', description: 'Pitch 1st control point x, y and z coordinate [m]', example: [7.537, 1.036, 0.063], type: 'array', items: new OA\Items(type: 'number', format: 'float')),
            new OA\Property(property: 'xyz_2', description: 'Pitch 2nd control point x, y and z coordinate [m]', example: [3.915, 1.166, 0.143], type: 'array', items: new OA\Items(type: 'number', format: 'float')),
            new OA\Property(property: 'xyz_3', description: 'Pitch 3rd control point x, y and z coordinate [m]', example: [0.434, 1.033, 0.193], type: 'array', items: new OA\Items(type: 'number', format: 'float'))
          ]
        )
      ]
    )
  )]
  #[OA\Response(
    response: 404,
    description: 'Pitch with given ID not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function pitchGetId(int $id): JsonResponse {
    $pitch = $this->entityManager->getRepository(Pitch::class)->find($id);
    if (!$pitch) return new JsonResponse(null, 404);

    return new JsonResponse([
      'id' => $pitch->getId(),
      'idx' => $pitch->getId(),
      'cnt' => $this->entityManager->getRepository(Pitch::class)->count([]),
      'date' => $pitch->getDate()?->format('d.m.Y'),
      'time' => $pitch->getTime()?->format('H:i'),
      'event' => [
        'id' => $pitch->getEvent()?->getId(),
        'organization' => $pitch->getEvent()?->getOrganization()?->getId(),
        'name' => $pitch->getEvent()?->getName(),
        'date' => $pitch->getEvent()?->getDate()?->format('d.m.Y'),
        'city' => $pitch->getEvent()?->getCity(),
        'country' => $pitch->getEvent()?->getCountry()?->getId(),
        'details' => $pitch->getEvent()?->getDetails(),
      ],
      'pitcher' => [
        'id' => $pitch->getPitcher()?->getId(),
        'organization' => $pitch->getPitcher()?->getOrganization()?->getId(),
        'firstName' => $pitch->getPitcher()?->getFirstName(),
        'lastName' => $pitch->getPitcher()?->getLastName(),
        'date' => $pitch->getPitcher()?->getDate()?->format('d.m.Y'),
        'city' => $pitch->getPitcher()?->getCity(),
        'country' => $pitch->getPitcher()?->getCountry()?->getId(),
        'details' => $pitch->getPitcher()?->getDetails(),
      ],
      'type' => $pitch->getType()?->getId(),
      't' => $pitch->getT(),
      'alpha' => $pitch->getAlpha(),
      'omega' => $pitch->getOmega(),
      'v_0' => $pitch->getV_0(),
      'phi_0' => $pitch->getPhi_0(),
      'theta_0' => $pitch->getTheta_0(),
      'v_t' => $pitch->getV_t(),
      'phi_t' => $pitch->getPhi_t(),
      'theta_t' => $pitch->getTheta_t(),
      'c_d' => $pitch->getC_d(),
      'c_l' => $pitch->getC_l(),
      'delta' => $pitch->getDelta(),
      'xyz_0' => $pitch->getXyz_0(),
      'xyz_t' => $pitch->getXyz_t(),
      'xyz_1' => $pitch->getXyz_1(),
      'xyz_2' => $pitch->getXyz_2(),
      'xyz_3' => $pitch->getXyz_3(),
    ]);
  }

  /**
   * @brief Gets one pitch by event, pitcher and index
   *
   * @param int $eventId event id
   * @param int $pitcherId pitcher id
   * @param int $idx pitch idx
   *
   * @return JsonResponse pitch
   */
  #[Route('/{eventId}/{pitcherId}/{idx}', methods: ['GET'])]
  #[OA\Get(
    summary: 'Pitch > Get one by event, pitcher',
    description: 'Gets one pitch by event, pitcher and index.',
    tags: ['Pitch']
  )]
  #[OA\PathParameter(
    name: 'eventId',
    description: 'Event ID (0 for all)',
    schema: new OA\Schema(type: 'integer', minimum: 0),
    required: true
  )]
  #[OA\PathParameter(
    name: 'pitcherId',
    description: 'Pitcher ID (0 for all)',
    schema: new OA\Schema(type: 'integer', minimum: 0),
    required: true
  )]
  #[OA\PathParameter(
    name: 'idx',
    description: 'Pitch index (1 for first)',
    schema: new OA\Schema(type: 'integer', minimum: 1),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns one pitch with given event, pitcher and index.',
    content: new OA\JsonContent(
      allOf: [
        new OA\Schema(
          properties: [
            new OA\Property(property: 'id', description: 'Pitch ID', example: 1, readOnly: true),
            new OA\Property(property: 'idx', description: 'Pitch index', example: 1, type: 'integer'),
            new OA\Property(property: 'cnt', description: 'Number of pitches (with given event and pitcher)', example: 199, type: 'integer'),
            new OA\Property(property: 'date', description: 'Pitch date', example: '29.07.2023', type: 'string', format: 'DD.MM.YYYY'),
            new OA\Property(property: 'time', description: 'Pitch date', example: '16:56', type: 'string', format: 'HH:MM'),
            new OA\Property(property: 'event', description: 'Pitch event', ref: new Model(type: Event::class)),
            new OA\Property(property: 'pitcher', description: 'Pitch pitcher', ref: new Model(type: Pitcher::class))
          ]
        ),
        new OA\Schema(ref: new Model(type: Pitch::class, groups: ['pitch:doc', 'pitch:get'])),
        new OA\Schema(
          properties: [
            new OA\Property(property: 'xyz_0', description: 'Pitch initial x, y and z coordinate [m]', example: [11.328, 0.599, -0.058], type: 'array', items: new OA\Items(type: 'number', format: 'float')),
            new OA\Property(property: 'xyz_t', description: 'Pitch final x, y and z coordinate [m]', example: [0.432, 1.040, 0.192], type: 'array', items: new OA\Items(type: 'number', format: 'float')),
            new OA\Property(property: 'xyz_1', description: 'Pitch 1st control point x, y and z coordinate [m]', example: [7.537, 1.036, 0.063], type: 'array', items: new OA\Items(type: 'number', format: 'float')),
            new OA\Property(property: 'xyz_2', description: 'Pitch 2nd control point x, y and z coordinate [m]', example: [3.915, 1.166, 0.143], type: 'array', items: new OA\Items(type: 'number', format: 'float')),
            new OA\Property(property: 'xyz_3', description: 'Pitch 3rd control point x, y and z coordinate [m]', example: [0.434, 1.033, 0.193], type: 'array', items: new OA\Items(type: 'number', format: 'float'))
          ]
        )
      ]
    )
  )]
  #[OA\Response(
    response: 404,
    description: 'Event, pitcher or pitch not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function pitchGetIdx(int $eventId, int $pitcherId, int $idx): JsonResponse {
    $criteria = [];

    if ($eventId) {
      $event = $this->entityManager->getRepository(Event::class)->find($eventId);
      if (!$event) return new JsonResponse(null, 404);
      $criteria['event'] = $event;
    }

    if ($pitcherId) {
      $pitcher = $this->entityManager->getRepository(Pitcher::class)->find($pitcherId);
      if (!$pitcher) return new JsonResponse(null, 404);
      $criteria['pitcher'] = $pitcher;
    }

    $pitch = $this->entityManager->getRepository(Pitch::class)->findBy($criteria, ['id' => 'ASC'], 1, $idx - 1);
    if (!$pitch) return new JsonResponse(null, 404);
    $pitch = $pitch[0];

    return new JsonResponse([
      'id' => $pitch->getId(),
      'idx' => $idx,
      'cnt' => $this->entityManager->getRepository(Pitch::class)->count($criteria),
      'date' => $pitch->getDate()?->format('d.m.Y'),
      'time' => $pitch->getTime()?->format('H:i'),
      'event' => [
        'id' => $pitch->getEvent()?->getId(),
        'organization' => $pitch->getEvent()?->getOrganization()?->getId(),
        'name' => $pitch->getEvent()?->getName(),
        'date' => $pitch->getEvent()?->getDate()?->format('d.m.Y'),
        'city' => $pitch->getEvent()?->getCity(),
        'country' => $pitch->getEvent()?->getCountry()?->getId(),
        'details' => $pitch->getEvent()?->getDetails(),
      ],
      'pitcher' => [
        'id' => $pitch->getPitcher()?->getId(),
        'organization' => $pitch->getPitcher()?->getOrganization()?->getId(),
        'firstName' => $pitch->getPitcher()?->getFirstName(),
        'lastName' => $pitch->getPitcher()?->getLastName(),
        'date' => $pitch->getPitcher()?->getDate()?->format('d.m.Y'),
        'city' => $pitch->getPitcher()?->getCity(),
        'country' => $pitch->getPitcher()?->getCountry()?->getId(),
        'details' => $pitch->getPitcher()?->getDetails(),
      ],
      'type' => $pitch->getType()?->getId(),
      't' => $pitch->getT(),
      'alpha' => $pitch->getAlpha(),
      'omega' => $pitch->getOmega(),
      'v_0' => $pitch->getV_0(),
      'phi_0' => $pitch->getPhi_0(),
      'theta_0' => $pitch->getTheta_0(),
      'v_t' => $pitch->getV_t(),
      'phi_t' => $pitch->getPhi_t(),
      'theta_t' => $pitch->getTheta_t(),
      'c_d' => $pitch->getC_d(),
      'c_l' => $pitch->getC_l(),
      'delta' => $pitch->getDelta(),
      'xyz_0' => $pitch->getXyz_0(),
      'xyz_t' => $pitch->getXyz_t(),
      'xyz_1' => $pitch->getXyz_1(),
      'xyz_2' => $pitch->getXyz_2(),
      'xyz_3' => $pitch->getXyz_3(),
    ]);
  }

  /**
   * @brief Adds new pitch
   *
   * @param Request $request request
   *
   * @return Response pitch id
   */
  #[Route('/', methods: ['POST'])]
  #[OA\Post(
    summary: 'Pitch > Add new',
    description: 'Adds new pitch.',
    tags: ['Pitch']
  )]
  #[OA\RequestBody(
    description: 'Pitch data',
    content: new Model(type: Pitch::class, groups: ['pitch:doc', 'pitch:set']),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns added pitch ID.',
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
  public function pitchAdd(Request $request): Response {
    $data = json_decode($request->getContent());

    $pitch = new Pitch();

    if (property_exists($data, 'date')) $pitch->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (property_exists($data, 'time')) $pitch->setTime(DateTime::createFromFormat('H:i', $data->time) ?: null);
    if (property_exists($data, 'event')) $pitch->setEvent($data->event ? $this->entityManager->getRepository(Event::class)?->find($data->event) ?? null : null);
    if (property_exists($data, 'pitcher')) $pitch->setPitcher($data->pitcher ? $this->entityManager->getRepository(Pitcher::class)?->find($data->pitcher) ?? null : null);
    if (property_exists($data, 'type')) $pitch->setType($data->type ? $this->entityManager->getRepository(Type::class)?->find($data->type) ?? null : null);
    if (property_exists($data, 't')) $pitch->setT($data->t);
    if (property_exists($data, 'alpha')) $pitch->setAlpha($data->alpha);
    if (property_exists($data, 'omega')) $pitch->setOmega($data->omega);
    if (property_exists($data, 'x_0')) $pitch->setX_0($data->x_0);
    if (property_exists($data, 'y_0')) $pitch->setY_0($data->y_0);
    if (property_exists($data, 'z_0')) $pitch->setZ_0($data->z_0);
    if (property_exists($data, 'v_0')) $pitch->setV_0($data->v_0);
    if (property_exists($data, 'phi_0')) $pitch->setPhi_0($data->phi_0);
    if (property_exists($data, 'theta_0')) $pitch->setTheta_0($data->theta_0);
    if (property_exists($data, 'x_t')) $pitch->setX_t($data->x_t);
    if (property_exists($data, 'y_t')) $pitch->setY_t($data->y_t);
    if (property_exists($data, 'z_t')) $pitch->setZ_t($data->z_t);
    if (property_exists($data, 'v_t')) $pitch->setV_t($data->v_t);
    if (property_exists($data, 'phi_t')) $pitch->setPhi_t($data->phi_t);
    if (property_exists($data, 'theta_t')) $pitch->setTheta_t($data->theta_t);

    PitchService::calc($pitch);

    $errors = $this->validator->validate($pitch);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->persist($pitch);
    $this->entityManager->flush();

    return new Response($pitch->getId());
  }

  /**
   * @brief Edits one pitch by id
   *
   * @param int $id pitch id
   * @param Request $request request
   *
   * @return Response pitch id
   */
  #[Route('/{id}', methods: ['PUT'])]
  #[OA\Put(
    summary: 'Pitch > Edit one',
    description: 'Edits one pitch by ID.',
    tags: ['Pitch']
  )]
  #[OA\PathParameter(
    name: 'id',
    description: 'Pitch ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\RequestBody(
    description: 'Pitch data',
    content: new Model(type: Pitch::class, groups: ['pitch:doc', 'pitch:set']),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns edited pitch ID.',
    content: new OA\JsonContent(type: 'integer', exclusiveMinimum: 0)
  )]
  #[OA\Response(
    response: 400,
    description: 'Validation error.'
  )]
  #[OA\Response(
    response: 404,
    description: 'Pitch with given ID not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function pitchEditId(int $id, Request $request): Response {
    $data = json_decode($request->getContent());

    $pitch = $this->entityManager->getRepository(Pitch::class)->find($id);
    if (!$pitch) return new Response(null, 404);

    if (property_exists($data, 'date')) $pitch->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (property_exists($data, 'time')) $pitch->setTime(DateTime::createFromFormat('H:i', $data->time) ?: null);
    if (property_exists($data, 'event')) $pitch->setEvent($data->event ? $this->entityManager->getRepository(Event::class)?->find($data->event) ?? null : null);
    if (property_exists($data, 'pitcher')) $pitch->setPitcher($data->pitcher ? $this->entityManager->getRepository(Pitcher::class)?->find($data->pitcher) ?? null : null);
    if (property_exists($data, 'type')) $pitch->setType($data->type ? $this->entityManager->getRepository(Type::class)?->find($data->type) ?? null : null);
    if (property_exists($data, 't')) $pitch->setT($data->t);
    if (property_exists($data, 'alpha')) $pitch->setAlpha($data->alpha);
    if (property_exists($data, 'omega')) $pitch->setOmega($data->omega);
    if (property_exists($data, 'x_0')) $pitch->setX_0($data->x_0);
    if (property_exists($data, 'y_0')) $pitch->setY_0($data->y_0);
    if (property_exists($data, 'z_0')) $pitch->setZ_0($data->z_0);
    if (property_exists($data, 'v_0')) $pitch->setV_0($data->v_0);
    if (property_exists($data, 'phi_0')) $pitch->setPhi_0($data->phi_0);
    if (property_exists($data, 'theta_0')) $pitch->setTheta_0($data->theta_0);
    if (property_exists($data, 'x_t')) $pitch->setX_t($data->x_t);
    if (property_exists($data, 'y_t')) $pitch->setY_t($data->y_t);
    if (property_exists($data, 'z_t')) $pitch->setZ_t($data->z_t);
    if (property_exists($data, 'v_t')) $pitch->setV_t($data->v_t);
    if (property_exists($data, 'phi_t')) $pitch->setPhi_t($data->phi_t);
    if (property_exists($data, 'theta_t')) $pitch->setTheta_t($data->theta_t);

    if (array_reduce(
      ['t', 'alpha', 'omega', 'x_0', 'y_0', 'z_0', 'v_0', 'phi_0', 'theta_0', 'x_t', 'y_t', 'z_t', 'v_t', 'phi_t', 'theta_t'],
      fn ($c, $p) => $c || property_exists($data, $p),
    )) {
      PitchService::calc($pitch);
    }

    $errors = $this->validator->validate($pitch);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->flush();

    return new Response($pitch->getId());
  }

  /**
   * @brief Deletes one pitch by id
   *
   * @param int $id pitch id
   *
   * @return Response pitch id
   */
  #[Route('/{id}', methods: ['DELETE'])]
  #[OA\Delete(
    summary: 'Pitch > Delete one',
    description: 'Deletes one pitch by ID.',
    tags: ['Pitch']
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns deleted pitch ID.',
    content: new OA\JsonContent(type: 'integer', exclusiveMinimum: 0)
  )]
  #[OA\Response(
    response: 404,
    description: 'Pitch with given ID not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function pitchDeleteId(int $id): Response {
    $pitch = $this->entityManager->getRepository(Pitch::class)->find($id);
    if (!$pitch) return new Response(null, 404);

    $this->entityManager->remove($pitch);
    $this->entityManager->flush();

    return new Response($pitch->getId());
  }

  /**
   * @brief Compares two pitches
   *
   * @param int $idA pitch A id
   * @param int $idB pitch B id
   *
   * @return JsonResponse comparison result
   */
  #[Route('/{idA}/{idB}', methods: ['GET'])]
  #[OA\Get(
    summary: 'Pitch > Compare two',
    description: 'Compares two pitches.',
    tags: ['Pitch']
  )]
  #[OA\PathParameter(
    name: 'idA',
    description: '1st pitch ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\PathParameter(
    name: 'idB',
    description: '2nd pitch ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns comparasion result.',
    content: new OA\JsonContent(
      allOf: [
        new OA\Schema(
          properties: [
            new OA\Property(property: 'half', description: 'First half pitch tunneling percentage', example: 0.793, type: 'number', format: 'float'),
            new OA\Property(property: 'last', description: 'Last point pitch tunneling percentage', example: 0.988, type: 'number', format: 'float'),
            new OA\Property(property: 'diff', description: 'First points difference [m]', example: 0.133, type: 'number', format: 'float')
          ]
        )
      ]
    )
  )]
  #[OA\Response(
    response: 404,
    description: '1st pitch or 2nd pitch not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function pitchCompId(int $idA, int $idB): JsonResponse  {
    $pitchA = $this->entityManager->getRepository(Pitch::class)->find($idA);
    if (!$pitchA) return new Response(null, 404);

    $pitchB = $this->entityManager->getRepository(Pitch::class)->find($idB);
    if (!$pitchB) return new Response(null, 404);

    return new JsonResponse([
      'half' => PitchService::compHalf($pitchA, $pitchB),
      'last' => PitchService::compLast($pitchA, $pitchB),
      'diff' => PitchService::compDiff($pitchA, $pitchB),
    ]);
  }
}
