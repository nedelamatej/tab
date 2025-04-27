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
 * @brief Event controller class
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

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\Pitcher;
use App\Entity\Pitch;
use App\Entity\Country;

use DateTime;

/**
 * @brief Event controller class
 */
#[Route('/event')]
final class EventController extends AbstractController {
  private $entityManager; ///< entity manager
  private $validator;     ///< validator

  /**
   * @brief Constructs new EventController object
   *
   * @param EntityManagerInterface $entityManager entity manager
   * @param ValidatorInterface $validator validator
   */
  public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator) {
    $this->entityManager = $entityManager;
    $this->validator = $validator;
  }

  /**
   * @brief Gets all events
   *
   * @return JsonResponse events
   */
  #[Route('/', methods: ['GET'])]
  #[OA\Get(
    summary: 'Event > Get all',
    description: 'Gets all events.',
    tags: ['Event']
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns all events.',
    content: new OA\JsonContent(
      type: 'array',
      items: new OA\Items(ref: new Model(type: Event::class))
    )
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function eventGet(): JsonResponse {
    $events = $this->entityManager->getRepository(Event::class)->findAll();

    return new JsonResponse(array_map(function ($event) {
      return [
        'id' => $event->getId(),
        'organization' => $event->getOrganization()?->getId(),
        'name' => $event->getName(),
        'date' => $event->getDate()?->format('d.m.Y'),
        'city' => $event->getCity(),
        'country' => $event->getCountry()?->getId(),
        'details' => $event->getDetails(),
      ];
    }, $events));
  }

  /**
   * @brief Gets all events by organization
   *
   * @param int $organizationId organization id
   *
   * @return JsonResponse events
   */
  #[Route('/organization/{organizationId}', methods: ['GET'])]
  #[OA\Get(
    summary: 'Event > Get all by organization',
    description: 'Gets all events by organization.',
    tags: ['Event']
  )]
  #[OA\PathParameter(
    name: 'organizationId',
    description: 'Organization ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns all events with given organization.',
    content: new OA\JsonContent(
      type: 'array',
      items: new OA\Items(ref: new Model(type: Event::class))
    )
  )]
  #[OA\Response(
    response: 404,
    description: 'Organization not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function eventGetOrganizationId(int $organizationId): JsonResponse {
    $organization = $this->entityManager->getRepository(Organization::class)->find($organizationId);
    if (!$organization) return new JsonResponse(null, 404);

    $events = $organization->getEvents();

    return new JsonResponse(array_map(function ($event) {
      return [
        'id' => $event->getId(),
        'organization' => $event->getOrganization()?->getId(),
        'name' => $event->getName(),
        'date' => $event->getDate()?->format('d.m.Y'),
        'city' => $event->getCity(),
        'country' => $event->getCountry()?->getId(),
        'details' => $event->getDetails(),
      ];
    }, $events->toArray()));
  }

  /**
   * @brief Gets all events by pitcher
   *
   * @param int $pitcherId pitcher id
   *
   * @return JsonResponse events
   */
  #[Route('/pitcher/{pitcherId}', methods: ['GET'])]
  #[OA\Get(
    summary: 'Event > Get all by pitcher',
    description: 'Gets all events by pitcher.',
    tags: ['Event']
  )]
  #[OA\PathParameter(
    name: 'pitcherId',
    description: 'Pitcher ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns all events with given pitcher.',
    content: new OA\JsonContent(
      type: 'array',
      items: new OA\Items(ref: new Model(type: Event::class))
    )
  )]
  #[OA\Response(
    response: 404,
    description: 'Pitcher not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function eventGetPitcherId(int $pitcherId): JsonResponse {
    $pitcher = $this->entityManager->getRepository(Pitcher::class)->find($pitcherId);
    if (!$pitcher) return new JsonResponse(null, 404);

    $pitches = $this->entityManager->getRepository(Pitch::class)->findBy(['pitcher' => $pitcher]);

    $events = array_unique(array_map(function ($pitch) {
      return $pitch->getEvent();
    }, $pitches), SORT_REGULAR);

    return new JsonResponse(array_map(function ($event) {
      return [
        'id' => $event->getId(),
        'organization' => $event->getOrganization()?->getId(),
        'name' => $event->getName(),
        'date' => $event->getDate()?->format('d.m.Y'),
        'city' => $event->getCity(),
        'country' => $event->getCountry()?->getId(),
        'details' => $event->getDetails(),
      ];
    }, $events));
  }

  /**
   * @brief Gets one event by id
   *
   * @param int $id event id
   *
   * @return JsonResponse event
   */
  #[OA\Get(
    summary: 'Event > Get one',
    description: 'Gets one event by ID.',
    tags: ['Event']
  )]
  #[OA\PathParameter(
    name: 'id',
    description: 'Event ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns one event.',
    content: new Model(type: Event::class)
  )]
  #[OA\Response(
    response: 404,
    description: 'Event not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  #[Route('/{id}', methods: ['GET'])]
  public function eventGetId(int $id): JsonResponse {
    $event = $this->entityManager->getRepository(Event::class)->find($id);
    if (!$event) return new JsonResponse(null, 404);

    return new JsonResponse([
      'id' => $event->getId(),
      'organization' => $event->getOrganization()?->getId(),
      'name' => $event->getName(),
      'date' => $event->getDate()?->format('d.m.Y'),
      'city' => $event->getCity(),
      'country' => $event->getCountry()?->getId(),
      'details' => $event->getDetails(),
    ]);
  }

  /**
   * @brief Adds new event
   *
   * @param Request $request request
   *
   * @return Response event id
   */
  #[Route('/', methods: ['POST'])]
  #[OA\Post(
    summary: 'Event > Add new',
    description: 'Adds new event.',
    tags: ['Event']
  )]
  #[OA\RequestBody(
    description: 'Event data',
    content: new Model(type: Event::class),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns added event ID.',
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
  public function eventAdd(Request $request): Response {
    $data = json_decode($request->getContent());

    $event = new Event();

    if (property_exists($data, 'organization')) $event->setOrganization($data->organization ? $this->entityManager->getRepository(Organization::class)?->find($data->organization) ?? null : null);
    if (property_exists($data, 'name')) $event->setName($data->name);
    if (property_exists($data, 'date')) $event->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (property_exists($data, 'city')) $event->setCity($data->city);
    if (property_exists($data, 'country')) $event->setCountry($data->country ? $this->entityManager->getRepository(Country::class)?->find($data->country) ?? null : null);
    if (property_exists($data, 'details')) $event->setDetails($data->details);

    $errors = $this->validator->validate($event);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->persist($event);
    $this->entityManager->flush();

    return new Response($event->getId());
  }

  /**
   * @brief Edits one event by id
   *
   * @param int $id event id
   * @param Request $request request
   *
   * @return Response event id
   */
  #[Route('/{id}', methods: ['PUT'])]
  #[OA\Put(
    summary: 'Event > Edit one',
    description: 'Edits one event by ID.',
    tags: ['Event']
  )]
  #[OA\PathParameter(
    name: 'id',
    description: 'Event ID',
    schema: new OA\Schema(type: 'integer', exclusiveMinimum: 0),
    required: true
  )]
  #[OA\RequestBody(
    description: 'Event data',
    content: new Model(type: Event::class),
    required: true
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns edited event ID.',
    content: new OA\JsonContent(type: 'integer', exclusiveMinimum: 0)
  )]
  #[OA\Response(
    response: 400,
    description: 'Validation error.'
  )]
  #[OA\Response(
    response: 404,
    description: 'Event not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function eventEditId(int $id, Request $request): Response {
    $data = json_decode($request->getContent());

    $event = $this->entityManager->getRepository(Event::class)->find($id);
    if (!$event) return new Response(null, 404);

    if (property_exists($data, 'organization')) $event->setOrganization($data->organization ? $this->entityManager->getRepository(Organization::class)?->find($data->organization) ?? null : null);
    if (property_exists($data, 'name')) $event->setName($data->name);
    if (property_exists($data, 'date')) $event->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (property_exists($data, 'city')) $event->setCity($data->city);
    if (property_exists($data, 'country')) $event->setCountry($data->country ? $this->entityManager->getRepository(Country::class)?->find($data->country) ?? null : null);
    if (property_exists($data, 'details')) $event->setDetails($data->details);

    $errors = $this->validator->validate($event);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->flush();

    return new Response($event->getId());
  }

  /**
   * @brief Deletes one event by id
   *
   * @param int $id event id
   *
   * @return Response event id
   */
  #[Route('/{id}', methods: ['DELETE'])]
  #[OA\Delete(
    summary: 'Event > Delete one',
    description: 'Deletes one event by ID.',
    tags: ['Event']
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns deleted event ID.',
    content: new OA\JsonContent(type: 'integer', exclusiveMinimum: 0)
  )]
  #[OA\Response(
    response: 404,
    description: 'Event not found.'
  )]
  #[OA\Response(
    response: 500,
    description: 'Server error.'
  )]
  public function eventDeleteId(int $id): Response {
    $event = $this->entityManager->getRepository(Event::class)->find($id);
    if (!$event) return new Response(null, 404);

    $this->entityManager->remove($event);
    $this->entityManager->flush();

    return new Response($event->getId());
  }
}
