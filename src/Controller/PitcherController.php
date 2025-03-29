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

    return new JsonResponse($pitchers);
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

    return new JsonResponse($pitcher);
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

    if (property_exists($data, 'organization')) $pitcher->setOrganization($this->entityManager->getRepository(Organization::class)?->find($data->organization) ?? null);
    if (property_exists($data, 'firstName')) $pitcher->setFirstName($data->firstName);
    if (property_exists($data, 'lastName')) $pitcher->setLastName($data->lastName);
    if (property_exists($data, 'date')) $pitcher->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (property_exists($data, 'city')) $pitcher->setCity($data->city);
    if (property_exists($data, 'country')) $pitcher->setCountry($data->country);
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

    if (property_exists($data, 'organization')) $pitcher->setOrganization($this->entityManager->getRepository(Organization::class)?->find($data->organization) ?? null);
    if (property_exists($data, 'firstName')) $pitcher->setFirstName($data->firstName);
    if (property_exists($data, 'lastName')) $pitcher->setLastName($data->lastName);
    if (property_exists($data, 'date')) $pitcher->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (property_exists($data, 'city')) $pitcher->setCity($data->city);
    if (property_exists($data, 'country')) $pitcher->setCountry($data->country);
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
