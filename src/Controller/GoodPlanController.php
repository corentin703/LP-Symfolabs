<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\GoodPlan;
use App\Entity\Promotion;
use App\Entity\Temperature;
use App\Event\BadgeTriggerEvent;
use App\Event\TemperatureAddedEvent;
use App\Form\CommentType;
use App\Form\GoodPlanType;
use App\Form\TemperatureType;
use App\Repository\CommentRepository;
use App\Repository\GoodPlanRepository;
use App\Repository\TemperatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/goodPlan")
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
    public function new(Request $request, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher): Response
    {
        $goodPlan = new GoodPlan();
        $form = $this->createForm(GoodPlanType::class, $goodPlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $goodPlan->setCreatedAt(new \DateTime('now'));
            $goodPlan->setAuthor($this->getUser());
            $entityManager->persist($goodPlan);
            $entityManager->flush();

            $eventDispatcher->dispatch(
                new BadgeTriggerEvent(
                    BadgeTriggerEvent::EVENT_DEAL_ADDED,
                    $this->getUser(),
                    $goodPlan,
                ),
                'badge.trigger'
            );

            return $this->redirectToRoute('good_plan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('good_plan/new.html.twig', [
            'good_plan' => $goodPlan,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="good_plan_show", methods={"GET", "POST"})
     */
    public function show(
        GoodPlan $goodPlan,
        Request $request,
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        TemperatureRepository $temperatureRepository
    ): Response {
        $comments = $commentRepository->findAllByPromotion($goodPlan->getId());

        $viewBag = [
            'good_plan' => $goodPlan,
            'comments' => $comments,
        ];

        if ($this->isGranted("IS_AUTHENTICATED_FULLY")) {
            $comment = new Comment();
            $commentForm = $this->createForm(CommentType::class, $comment);
            $commentForm->handleRequest($request);

            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $comment->setCreatedAt(new \DateTime('now'));
                $comment->setPromotion($goodPlan);
                $comment->setAuthor($this->getUser());

                $entityManager->persist($comment);
                $entityManager->flush();

                $eventDispatcher->dispatch(
                    new BadgeTriggerEvent(
                        BadgeTriggerEvent::EVENT_COMMENT_ADDED,
                        $this->getUser(),
                        $goodPlan,
                    ),
                    'badge.trigger'
                );

                return $this->redirectToRoute(
                    'good_plan_show',
                    [
                        'id' => $goodPlan->getId(),
                    ],
                    Response::HTTP_SEE_OTHER
                );
            }

            $response = $this->handleTemperatureForm(
                $goodPlan,
                $request,
                $entityManager,
                $temperatureRepository,
                $eventDispatcher,
                $viewBag
            );

            if ($response !== null) {
                return $response;
            }

            $viewBag['comment_form'] = $commentForm->createView();
        }

        return $this->render('good_plan/show.html.twig', $viewBag);
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

    private function handleTemperatureForm(
        GoodPlan $goodPlan,
        Request $request,
        EntityManagerInterface $entityManager,
        TemperatureRepository $temperatureRepository,
        EventDispatcherInterface $eventDispatcher,
        array& $viewBag
    ): ?Response {
        $temperaturesForPromotion = $temperatureRepository->getPromotionTemperatureByUser(
            $goodPlan->getId(),
            $this->getUser()->getId()
        );

        if ($temperaturesForPromotion !== null && count($temperaturesForPromotion) > 0) {
            return null;
        }

        $temperature = new Temperature();
        $temperatureForm = $this->createForm(TemperatureType::class);
        $temperatureForm->handleRequest($request);

        if ($temperatureForm->isSubmitted() && $temperatureForm->isValid()) {
            $temperature->setPromotion($goodPlan);
            $temperature->setUser($this->getUser());

            $positiveBtn = $temperatureForm->get('positive');
            $negativeBtn = $temperatureForm->get('negative');

            if ($positiveBtn !== null) {
                $temperature->setPositive($positiveBtn->isClicked());
            } else if ($negativeBtn !== null) {
                $temperature->setPositive(!$negativeBtn->isClicked());
            } else {
                return null;
            }

            $entityManager->persist($temperature);
            $entityManager->flush();

            $eventDispatcher->dispatch(
                new BadgeTriggerEvent(
                    BadgeTriggerEvent::EVENT_DEAL_VOTED,
                    $this->getUser(),
                    $goodPlan,
                ),
                'badge.trigger'
            );

            $eventDispatcher->dispatch(new TemperatureAddedEvent($goodPlan), 'temperature.added');

            return $this->redirectToRoute(
                'good_plan_show',
                [
                    'id' => $goodPlan->getId(),
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        $viewBag['temperature_form'] = $temperatureForm->createView();
        return null;
    }
}
