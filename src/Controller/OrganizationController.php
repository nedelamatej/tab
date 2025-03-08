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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Organization;

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
  #[Route(['/', '/get'], methods: ['GET'])]
  public function organizationGet(): JsonResponse {
    $organizations = $this->entityManager->getRepository(Organization::class)->findAll();

    return new JsonResponse($organizations);
  }

  /**
   * @brief Gets one organization by id
   *
   * @param int $id organization id
   *
   * @return JsonResponse organization
   */
  #[Route(['/{id}', '/get/{id}'], methods: ['GET'])]
  public function organizationGetId(int $id): JsonResponse {
    $organization = $this->entityManager->getRepository(Organization::class)->find($id);
    if (!$organization) return new Response(null, 404);

    return new JsonResponse($organization);
  }

  /**
   * @brief Adds new organization
   *
   * @param Request $request request
   *
   * @return Response organization id
   */
  #[Route(['/', '/add'], methods: ['POST'])]
  public function organizationAdd(Request $request): Response {
    $data = json_decode($request->getContent());

    $organization = new Organization();

    if (isset($data->name)) $organization->setName($data->name);
    if (isset($data->date)) $organization->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (isset($data->city)) $organization->setCity($data->city);
    if (isset($data->country)) $organization->setCountry($data->country);
    if (isset($data->details)) $organization->setDetails($data->details);

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
  #[Route(['/{id}', '/edit/{id}'], methods: ['PUT'])]
  public function organizationEditId(int $id, Request $request): Response {
    $data = json_decode($request->getContent());

    $organization = $this->entityManager->getRepository(Organization::class)->find($id);
    if (!$organization) return new Response(null, 404);

    if (isset($data->name)) $organization->setName($data->name);
    if (isset($data->date)) $organization->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (isset($data->city)) $organization->setCity($data->city);
    if (isset($data->country)) $organization->setCountry($data->country);
    if (isset($data->details)) $organization->setDetails($data->details);

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
  #[Route(['/{id}', '/delete/{id}'], methods: ['DELETE'])]
  public function organizationDeleteId(int $id): Response {
    $organization = $this->entityManager->getRepository(Organization::class)->find($id);
    if (!$organization) return new Response(null, 404);

    $this->entityManager->remove($organization);
    $this->entityManager->flush();

    return new Response($organization->getId());
  }
}
