<?php

namespace App\Controller;

use App\Entity\GoodPlan;
use App\Form\GoodPlanType;
use App\Repository\GoodPlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/good/plan")
 */
class GoodPlanController extends AbstractController
{
    /**
     * @Route("/", name="good_plan_index", methods={"GET"})
     */
    public function index(GoodPlanRepository $goodPlanRepository): Response
    {
        return $this->render('good_plan/index.html.twig', [
            'good_plans' => $goodPlanRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="good_plan_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $goodPlan = new GoodPlan();
        $form = $this->createForm(GoodPlanType::class, $goodPlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($goodPlan);
            $entityManager->flush();

            return $this->redirectToRoute('good_plan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('good_plan/new.html.twig', [
            'good_plan' => $goodPlan,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="good_plan_show", methods={"GET"})
     */
    public function show(GoodPlan $goodPlan): Response
    {
        return $this->render('good_plan/show.html.twig', [
            'good_plan' => $goodPlan,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="good_plan_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, GoodPlan $goodPlan, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GoodPlanType::class, $goodPlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('good_plan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('good_plan/edit.html.twig', [
            'good_plan' => $goodPlan,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="good_plan_delete", methods={"POST"})
     */
    public function delete(Request $request, GoodPlan $goodPlan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$goodPlan->getId(), $request->request->get('_token'))) {
            $entityManager->remove($goodPlan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('good_plan_index', [], Response::HTTP_SEE_OTHER);
    }
}
