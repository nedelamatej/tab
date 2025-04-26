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
  #[Route(['/', '/get'], methods: ['GET'])]
  public function pitchGet(): JsonResponse {
    $pitches = $this->entityManager->getRepository(Pitch::class)->findAll();

    return new JsonResponse(array_map(function ($pitch) {
      return [
        'id' => $pitch->getId(),
        'idx' => $pitch->getId(),
        'cnt' => $this->entityManager->getRepository(Pitch::class)->count([]),
        'date' => $pitch->getDate()?->format('d.m.Y'),
        'time' => $pitch->getTime()?->format('H:i'),
        'event' => $pitch->getEvent()?->getId(),
        'pitcher' => $pitch->getPitcher()?->getId(),
        'type' => $pitch->getType()?->getId(),
        't' => $pitch->getT(),
        'alpha' => $pitch->getAlpha(),
        'omega' => $pitch->getOmega(),
        'xyz_0' => $pitch->getXyz_0(),
        'v_0' => $pitch->getV_0(),
        'phi_0' => $pitch->getPhi_0(),
        'theta_0' => $pitch->getTheta_0(),
        'xyz_t' => $pitch->getXyz_t(),
        'v_t' => $pitch->getV_t(),
        'phi_t' => $pitch->getPhi_t(),
        'theta_t' => $pitch->getTheta_t(),
        'xyz_1' => $pitch->getXyz_1(),
        'xyz_2' => $pitch->getXyz_2(),
        'xyz_3' => $pitch->getXyz_3(),
        'c_d' => $pitch->getC_d(),
        'c_l' => $pitch->getC_l(),
        'delta' => $pitch->getDelta(),
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
  #[Route(['/{id}', '/get/{id}'], methods: ['GET'])]
  public function pitchGetId(int $id): JsonResponse {
    $pitch = $this->entityManager->getRepository(Pitch::class)->find($id);
    if (!$pitch) return new JsonResponse(null, 404);

    return new JsonResponse([
      'id' => $pitch->getId(),
      'idx' => $pitch->getId(),
      'cnt' => $this->entityManager->getRepository(Pitch::class)->count([]),
      'date' => $pitch->getDate()?->format('d.m.Y'),
      'time' => $pitch->getTime()?->format('H:i'),
      'event' => $pitch->getEvent()?->getId(),
      'pitcher' => $pitch->getPitcher()?->getId(),
      'type' => $pitch->getType()?->getId(),
      't' => $pitch->getT(),
      'alpha' => $pitch->getAlpha(),
      'omega' => $pitch->getOmega(),
      'xyz_0' => $pitch->getXyz_0(),
      'v_0' => $pitch->getV_0(),
      'phi_0' => $pitch->getPhi_0(),
      'theta_0' => $pitch->getTheta_0(),
      'xyz_t' => $pitch->getXyz_t(),
      'v_t' => $pitch->getV_t(),
      'phi_t' => $pitch->getPhi_t(),
      'theta_t' => $pitch->getTheta_t(),
      'xyz_1' => $pitch->getXyz_1(),
      'xyz_2' => $pitch->getXyz_2(),
      'xyz_3' => $pitch->getXyz_3(),
      'c_d' => $pitch->getC_d(),
      'c_l' => $pitch->getC_l(),
      'delta' => $pitch->getDelta(),
    ]);
  }

  /**
   * @brief Gets one pitch by idx
   *
   * @param int $eventId event id
   * @param int $pitcherId pitcher id
   * @param int $idx pitch idx
   *
   * @return JsonResponse pitch
   */
  #[Route(['/{eventId}/{pitcherId}/{idx}', '/get/{eventId}/{pitcherId}/{idx}'], methods: ['GET'])]
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
      'event' => $pitch->getEvent()?->getId(),
      'pitcher' => $pitch->getPitcher()?->getId(),
      'type' => $pitch->getType()?->getId(),
      't' => $pitch->getT(),
      'alpha' => $pitch->getAlpha(),
      'omega' => $pitch->getOmega(),
      'xyz_0' => $pitch->getXyz_0(),
      'v_0' => $pitch->getV_0(),
      'phi_0' => $pitch->getPhi_0(),
      'theta_0' => $pitch->getTheta_0(),
      'xyz_t' => $pitch->getXyz_t(),
      'v_t' => $pitch->getV_t(),
      'phi_t' => $pitch->getPhi_t(),
      'theta_t' => $pitch->getTheta_t(),
      'xyz_1' => $pitch->getXyz_1(),
      'xyz_2' => $pitch->getXyz_2(),
      'xyz_3' => $pitch->getXyz_3(),
      'c_d' => $pitch->getC_d(),
      'c_l' => $pitch->getC_l(),
      'delta' => $pitch->getDelta(),
    ]);
  }

  /**
   * @brief Adds new pitch
   *
   * @param Request $request request
   *
   * @return Response pitch id
   */
  #[Route(['/', '/add'], methods: ['POST'])]
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
  #[Route(['/{id}', '/edit/{id}'], methods: ['PUT'])]
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
  #[Route(['/{id}', '/delete/{id}'], methods: ['DELETE'])]
  public function pitchDeleteId(int $id): Response {
    $pitch = $this->entityManager->getRepository(Pitch::class)->find($id);
    if (!$pitch) return new Response(null, 404);

    $this->entityManager->remove($pitch);
    $this->entityManager->flush();

    return new Response($pitch->getId());
  }
}
