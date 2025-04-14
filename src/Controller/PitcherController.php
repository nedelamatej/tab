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
 * @brief Pitcher controller class
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

use App\Entity\Pitcher;
use App\Entity\Organization;
use App\Entity\Event;
use App\Entity\Pitch;
use App\Entity\Country;

use DateTime;

/**
 * @brief Pitcher controller class
 */
#[Route('/pitcher')]
final class PitcherController extends AbstractController {
  private $entityManager; ///< entity manager
  private $validator;     ///< validator

  /**
   * @brief Constructs new PitcherController object
   *
   * @param EntityManagerInterface $entityManager entity manager
   * @param ValidatorInterface $validator validator
   */
  public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator) {
    $this->entityManager = $entityManager;
    $this->validator = $validator;
  }

  /**
   * @brief Gets all pitchers
   *
   * @return JsonResponse pitchers
   */
  #[Route(['/', '/get'], methods: ['GET'])]
  public function pitcherGet(): JsonResponse {
    $pitchers = $this->entityManager->getRepository(Pitcher::class)->findAll();

    return new JsonResponse(array_map(function ($pitcher) {
      return [
        'id' => $pitcher->getId(),
        'organization' => $pitcher->getOrganization()?->getId(),
        'firstName' => $pitcher->getFirstName(),
        'lastName' => $pitcher->getLastName(),
        'date' => $pitcher->getDate()?->format('d.m.Y'),
        'city' => $pitcher->getCity(),
        'country' => $pitcher->getCountry()?->getId(),
        'details' => $pitcher->getDetails(),
      ];
    }, $pitchers));
  }

  /**
   * @brief Gets all pitchers by organization
   *
   * @param int $organizationId organization id
   *
   * @return JsonResponse pitchers
   */
  #[Route(['/organization/{organizationId}', '/get/organization/{organizationId}'], methods: ['GET'])]
  public function pitcherGetOrganizationId(int $organizationId): JsonResponse {
    $organization = $this->entityManager->getRepository(Organization::class)->find($organizationId);
    if (!$organization) return new Response(null, 404);

    $pitchers = $organization->getPitchers();

    return new JsonResponse(array_map(function ($pitcher) {
      return [
        'id' => $pitcher->getId(),
        'organization' => $pitcher->getOrganization()?->getId(),
        'firstName' => $pitcher->getFirstName(),
        'lastName' => $pitcher->getLastName(),
        'date' => $pitcher->getDate()?->format('d.m.Y'),
        'city' => $pitcher->getCity(),
        'country' => $pitcher->getCountry()?->getId(),
        'details' => $pitcher->getDetails(),
      ];
    }, $pitchers->toArray()));
  }

  /**
   * @brief Gets all pitchers by event
   *
   * @param int $eventId event id
   *
   * @return JsonResponse pitchers
   */
  #[Route(['/event/{eventId}', '/get/event/{eventId}'], methods: ['GET'])]
  public function pitcherGetEventId(int $eventId): JsonResponse {
    $event = $this->entityManager->getRepository(Event::class)->find($eventId);
    if (!$event) return new Response(null, 404);

    $pitches = $this->entityManager->getRepository(Pitch::class)->findBy(['event' => $event]);

    $pitchers = array_unique(array_map(function ($pitch) {
      return $pitch->getPitcher();
    }, $pitches), SORT_REGULAR);

    return new JsonResponse(array_map(function ($pitcher) {
      return [
        'id' => $pitcher->getId(),
        'organization' => $pitcher->getOrganization()?->getId(),
        'firstName' => $pitcher->getFirstName(),
        'lastName' => $pitcher->getLastName(),
        'date' => $pitcher->getDate()?->format('d.m.Y'),
        'city' => $pitcher->getCity(),
        'country' => $pitcher->getCountry()?->getId(),
        'details' => $pitcher->getDetails(),
      ];
    }, $pitchers));
  }

  /**
   * @brief Gets one pitcher by id
   *
   * @param int $id pitcher id
   *
   * @return JsonResponse pitcher
   */
  #[Route(['/{id}', '/get/{id}'], methods: ['GET'])]
  public function pitcherGetId(int $id): JsonResponse {
    $pitcher = $this->entityManager->getRepository(Pitcher::class)->find($id);
    if (!$pitcher) return new Response(null, 404);

    return new JsonResponse([
      'id' => $pitcher->getId(),
      'organization' => $pitcher->getOrganization()?->getId(),
      'firstName' => $pitcher->getFirstName(),
      'lastName' => $pitcher->getLastName(),
      'date' => $pitcher->getDate()?->format('d.m.Y'),
      'city' => $pitcher->getCity(),
      'country' => $pitcher->getCountry()?->getId(),
      'details' => $pitcher->getDetails(),
    ]);
  }

  /**
   * @brief Adds new pitcher
   *
   * @param Request $request request
   *
   * @return Response pitcher id
   */
  #[Route(['/', '/add'], methods: ['POST'])]
  public function pitcherAdd(Request $request): Response {
    $data = json_decode($request->getContent());

    $pitcher = new Pitcher();

    if (property_exists($data, 'organization')) $pitcher->setOrganization($data->organizatoin ? $this->entityManager->getRepository(Organization::class)?->find($data->organization) ?? null : null);
    if (property_exists($data, 'firstName')) $pitcher->setFirstName($data->firstName);
    if (property_exists($data, 'lastName')) $pitcher->setLastName($data->lastName);
    if (property_exists($data, 'date')) $pitcher->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (property_exists($data, 'city')) $pitcher->setCity($data->city);
    if (property_exists($data, 'country')) $pitcher->setCountry($data->country ? $this->entityManager->getRepository(Country::class)?->find($data->country) ?? null : null);
    if (property_exists($data, 'details')) $pitcher->setDetails($data->details);

    $errors = $this->validator->validate($pitcher);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->persist($pitcher);
    $this->entityManager->flush();

    return new Response($pitcher->getId());
  }

  /**
   * @brief Edits one pitcher by id
   *
   * @param int $id pitcher id
   * @param Request $request request
   *
   * @return Response pitcher id
   */
  #[Route(['/{id}', '/edit/{id}'], methods: ['PUT'])]
  public function pitcherEditId(int $id, Request $request): Response {
    $data = json_decode($request->getContent());

    $pitcher = $this->entityManager->getRepository(Pitcher::class)->find($id);
    if (!$pitcher) return new Response(null, 404);

    if (property_exists($data, 'organization')) $pitcher->setOrganization($data->organization ? $this->entityManager->getRepository(Organization::class)?->find($data->organization) ?? null : null);
    if (property_exists($data, 'firstName')) $pitcher->setFirstName($data->firstName);
    if (property_exists($data, 'lastName')) $pitcher->setLastName($data->lastName);
    if (property_exists($data, 'date')) $pitcher->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (property_exists($data, 'city')) $pitcher->setCity($data->city);
    if (property_exists($data, 'country')) $pitcher->setCountry($data->country ? $this->entityManager->getRepository(Country::class)?->find($data->country) ?? null : null);
    if (property_exists($data, 'details')) $pitcher->setDetails($data->details);

    $errors = $this->validator->validate($pitcher);
    if (count($errors) > 0) return new Response($errors, 400);

    $this->entityManager->flush();

    return new Response($pitcher->getId());
  }

  /**
   * @brief Deletes one pitcher by id
   *
   * @param int $id pitcher id
   *
   * @return Response pitcher id
   */
  #[Route(['/{id}', '/delete/{id}'], methods: ['DELETE'])]
  public function pitcherDeleteId(int $id): Response {
    $pitcher = $this->entityManager->getRepository(Pitcher::class)->find($id);
    if (!$pitcher) return new Response(null, 404);

    $this->entityManager->remove($pitcher);
    $this->entityManager->flush();

    return new Response($pitcher->getId());
  }
}
