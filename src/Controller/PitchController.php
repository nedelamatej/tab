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

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

use App\Entity\Pitch;
use App\Entity\Event;
use App\Entity\Pitcher;
use App\Entity\Type;

use App\Service\BernsteinPolynomial;
use App\Service\DifferentialEquations\SoftballEquations;
use App\Service\DistanceMetric\EuclideanDistance;
use App\Service\NeighborhoodSearch\MooreNeighborhood;
use App\Service\NumericalMethod\RungeKutta4Method;

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

    return new JsonResponse($pitches);
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
    if (!$pitch) return new Response(null, 404);

    return new JsonResponse($pitch);
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

    if (isset($data->date)) $pitch->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (isset($data->time)) $pitch->setTime(DateTime::createFromFormat('H:i', $data->time) ?: null);
    if (isset($data->event)) $pitch->setEvent($this->entityManager->getRepository(Event::class)?->find($data->event) ?? null);
    if (isset($data->pitcher)) $pitch->setPitcher($this->entityManager->getRepository(Pitcher::class)?->find($data->pitcher) ?? null);
    if (isset($data->type)) $pitch->setType($this->entityManager->getRepository(Type::class)?->find($data->type) ?? null);
    if (isset($data->t)) $pitch->setT($data->t);
    if (isset($data->alpha)) $pitch->setAlpha($data->alpha);
    if (isset($data->omega)) $pitch->setOmega($data->omega);
    if (isset($data->x_0)) $pitch->setX_0($data->x_0);
    if (isset($data->y_0)) $pitch->setY_0($data->y_0);
    if (isset($data->z_0)) $pitch->setZ_0($data->z_0);
    if (isset($data->v_0)) $pitch->setV_0($data->v_0);
    if (isset($data->phi_0)) $pitch->setPhi_0($data->phi_0);
    if (isset($data->theta_0)) $pitch->setTheta_0($data->theta_0);
    if (isset($data->x_t)) $pitch->setX_t($data->x_t);
    if (isset($data->y_t)) $pitch->setY_t($data->y_t);
    if (isset($data->z_t)) $pitch->setZ_t($data->z_t);
    if (isset($data->v_t)) $pitch->setV_t($data->v_t);
    if (isset($data->phi_t)) $pitch->setPhi_t($data->phi_t);
    if (isset($data->theta_t)) $pitch->setTheta_t($data->theta_t);

    $mooreNeighborhood = new MooreNeighborhood(0.2, 0.0, 0.5, 1.0);
    $rungeKutta4Method = new RungeKutta4Method(99, $pitch->getT());

    $state = [...$pitch->getXyz_0(), ...$pitch->getV_xyz_0()];

    [$c_d, $c_l] = $mooreNeighborhood->approx(function ($c_d, $c_l) use ($pitch, $state, $rungeKutta4Method) {
      $softballEquations = new SoftballEquations($c_d, $c_l, $pitch->getAlpha());

      $solution = $rungeKutta4Method->solve($state, [$softballEquations, 'deriv']);

      return EuclideanDistance::calc($pitch->getXyz_t(), end($solution));
    });

    $softballEquations = new SoftballEquations($c_l, $c_d, $pitch->getAlpha());

    $solution = $rungeKutta4Method->solve($state, [$softballEquations, 'deriv']);

    $matrix = [];

    for ($i = 0; $i < count($solution); ++$i) {
      for ($j = 0; $j < 4; ++$j) {
        $matrix[$i][$j] = BernsteinPolynomial::calc($j, 4, 1 / (count($solution) - 1) * $i);
      }
    }

    $B = MatrixFactory::createNumeric($matrix);
    $BTBBT = $B->transpose()->multiply($B)->inverse()->multiply($B->transpose());

    $x = $BTBBT->vectorMultiply(new Vector(array_column($solution, 0)));
    $y = $BTBBT->vectorMultiply(new Vector(array_column($solution, 1)));
    $z = $BTBBT->vectorMultiply(new Vector(array_column($solution, 2)));

    $pitch->setXyz_1([$x->get(1), $y->get(1), $z->get(1)]);
    $pitch->setXyz_2([$x->get(2), $y->get(2), $z->get(2)]);

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

    if (isset($data->date)) $pitch->setDate(DateTime::createFromFormat('d.m.Y', $data->date) ?: null);
    if (isset($data->time)) $pitch->setTime(DateTime::createFromFormat('H:i', $data->time) ?: null);
    if (isset($data->event)) $pitch->setEvent($this->entityManager->getRepository(Event::class)?->find($data->event) ?? null);
    if (isset($data->pitcher)) $pitch->setPitcher($this->entityManager->getRepository(Pitcher::class)?->find($data->pitcher) ?? null);
    if (isset($data->type)) $pitch->setType($this->entityManager->getRepository(Type::class)?->find($data->type) ?? null);
    if (isset($data->t)) $pitch->setT($data->t);
    if (isset($data->alpha)) $pitch->setAlpha($data->alpha);
    if (isset($data->omega)) $pitch->setOmega($data->omega);
    if (isset($data->x_0)) $pitch->setX_0($data->x_0);
    if (isset($data->y_0)) $pitch->setY_0($data->y_0);
    if (isset($data->z_0)) $pitch->setZ_0($data->z_0);
    if (isset($data->v_0)) $pitch->setV_0($data->v_0);
    if (isset($data->phi_0)) $pitch->setPhi_0($data->phi_0);
    if (isset($data->theta_0)) $pitch->setTheta_0($data->theta_0);
    if (isset($data->x_t)) $pitch->setX_t($data->x_t);
    if (isset($data->y_t)) $pitch->setY_t($data->y_t);
    if (isset($data->z_t)) $pitch->setZ_t($data->z_t);
    if (isset($data->v_t)) $pitch->setV_t($data->v_t);
    if (isset($data->phi_t)) $pitch->setPhi_t($data->phi_t);
    if (isset($data->theta_t)) $pitch->setTheta_t($data->theta_t);

    $mooreNeighborhood = new MooreNeighborhood(0.2, 0.0, 0.5, 1.0);
    $rungeKutta4Method = new RungeKutta4Method(99, $pitch->getT());

    $state = [...$pitch->getXyz_0(), ...$pitch->getV_xyz_0()];

    [$c_d, $c_l] = $mooreNeighborhood->approx(function ($c_d, $c_l) use ($pitch, $state, $rungeKutta4Method) {
      $softballEquations = new SoftballEquations($c_d, $c_l, $pitch->getAlpha());

      $solution = $rungeKutta4Method->solve($state, [$softballEquations, 'deriv']);

      return EuclideanDistance::calc($pitch->getXyz_t(), end($solution));
    });

    $softballEquations = new SoftballEquations($c_l, $c_d, $pitch->getAlpha());

    $solution = $rungeKutta4Method->solve($state, [$softballEquations, 'deriv']);

    $matrix = [];

    for ($i = 0; $i < count($solution); ++$i) {
      for ($j = 0; $j < 4; ++$j) {
        $matrix[$i][$j] = BernsteinPolynomial::calc($j, 4, 1 / (count($solution) - 1) * $i);
      }
    }

    $B = MatrixFactory::createNumeric($matrix);
    $BTBBT = $B->transpose()->multiply($B)->inverse()->multiply($B->transpose());

    $x = $BTBBT->vectorMultiply(new Vector(array_column($solution, 0)));
    $y = $BTBBT->vectorMultiply(new Vector(array_column($solution, 1)));
    $z = $BTBBT->vectorMultiply(new Vector(array_column($solution, 2)));

    $pitch->setXyz_1([$x->get(1), $y->get(1), $z->get(1)]);
    $pitch->setXyz_2([$x->get(2), $y->get(2), $z->get(2)]);

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
