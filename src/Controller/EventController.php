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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Event;
use App\Entity\Organization;

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
  #[Route(['/', '/get'], methods: ['GET'])]
  public function eventGet(): JsonResponse {
    $events = $this->entityManager->getRepository(Event::class)->findAll();

    return new JsonResponse($events);
  }

  /**
   * @brief Gets one event by id
   *
   * @param int $id event id
   *
   * @return JsonResponse event
   */
  #[Route(['/{id}', '/get/{id}'], methods: ['GET'])]
  public function eventGetId(int $id): JsonResponse {
    $event = $this->entityManager->getRepository(Event::class)->find($id);
    if (!$event) return new Response(null, 404);

    return new JsonResponse($event);
  }

  /**
   * @brief Adds new event
   *
   * @param Request $request request
   *
   * @return Response event id
   */
  #[Route(['/', '/add'], methods: ['POST'])]
  public function eventAdd(Request $request): Response {
    $data = json_decode($request->getContent());

    $event = new Event();

    if (isset($data->organization)) $event->setOrganization($this->entityManager->getRepository(Organization::class)?->find($data->organization) ?? null);
    if (isset($data->name)) $event->setName($data->name);
    if (isset($data->date)) $event->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (isset($data->city)) $event->setCity($data->city);
    if (isset($data->country)) $event->setCountry($data->country);
    if (isset($data->details)) $event->setDetails($data->details);

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
  #[Route(['/{id}', '/edit/{id}'], methods: ['PUT'])]
  public function eventEditId(int $id, Request $request): Response {
    $data = json_decode($request->getContent());

    $event = $this->entityManager->getRepository(Event::class)->find($id);
    if (!$event) return new Response(null, 404);

    if (isset($data->organization)) $event->setOrganization($this->entityManager->getRepository(Organization::class)?->find($data->organization) ?? null);
    if (isset($data->name)) $event->setName($data->name);
    if (isset($data->date)) $event->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (isset($data->city)) $event->setCity($data->city);
    if (isset($data->country)) $event->setCountry($data->country);
    if (isset($data->details)) $event->setDetails($data->details);

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
  #[Route(['/{id}', '/delete/{id}'], methods: ['DELETE'])]
  public function eventDeleteId(int $id): Response {
    $event = $this->entityManager->getRepository(Event::class)->find($id);
    if (!$event) return new Response(null, 404);

    $this->entityManager->remove($event);
    $this->entityManager->flush();

    return new Response($event->getId());
  }
}
