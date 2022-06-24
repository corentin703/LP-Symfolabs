<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Promotion;
use App\Entity\Temperature;
use App\Event\BadgeTriggerEvent;
use App\Event\TemperatureAddedEvent;
use App\Form\CommentType;
use App\Form\PromotionType;
use App\Form\TemperatureType;
use App\Repository\CommentRepository;
use App\Repository\PromotionRepository;
use App\Repository\TemperatureRepository;
use App\Service\PromotionReportingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/promotion")
 */
class PromotionController extends AbstractController
{
    /**
     * @Route("/", name="promotion_index", methods={"GET"})
     */
    public function index(PromotionRepository $promotionRepository): Response
    {
        return $this->render('promotion/index.html.twig', [
            'promotions' => $promotionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="promotion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, EventDispatcherInterface  $eventDispatcher): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotion->setCreatedAt(new \DateTime('now'));
            $promotion->setAuthor($this->getUser());
            $entityManager->persist($promotion);
            $entityManager->flush();

            $eventDispatcher->dispatch(
                new BadgeTriggerEvent(
                    BadgeTriggerEvent::EVENT_DEAL_ADDED,
                    $this->getUser(),
                    $promotion,
                ),
                'badge.trigger'
            );

            return $this->redirectToRoute('promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promotion/new.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="promotion_show", methods={"GET", "POST"})
     */
    public function show(
        Promotion $promotion,
        Request $request,
        CommentRepository $commentRepository,
        TemperatureRepository $temperatureRepository,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        $comments = $commentRepository->findAllByPromotion($promotion->getId());

        $viewBag = [
            'promotion' => $promotion,
            'comments' => $comments,
        ];

        if ($this->isGranted("IS_AUTHENTICATED_FULLY")) {
            $comment = new Comment();
            $commentForm = $this->createForm(CommentType::class, $comment);
            $commentForm->handleRequest($request);

            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $comment->setCreatedAt(new \DateTime('now'));
                $comment->setPromotion($promotion);
                $comment->setAuthor($this->getUser());

                $entityManager->persist($comment);
                $entityManager->flush();

                $eventDispatcher->dispatch(
                    new BadgeTriggerEvent(
                        BadgeTriggerEvent::EVENT_COMMENT_ADDED,
                        $this->getUser(),
                        $promotion,
                    ),
                    'badge.trigger'
                );

                return $this->redirectToRoute(
                    'promotion_show',
                    [
                        'id' => $promotion->getId(),
                    ],
                    Response::HTTP_SEE_OTHER
                );
            }

            $response = $this->handleTemperatureForm(
                $promotion,
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


        $promotion->setViewCount($promotion->getViewCount() + 1);
        $entityManager->persist($promotion);
        $entityManager->flush();

        return $this->render('promotion/show.html.twig', $viewBag);
    }

    /**
     * @Route("/{id}/report", name="promotion_report", methods={"POST"})
     */
    public function report(Promotion $promotion, PromotionReportingService $promotionReportingService) {
        $promotionReportingService->reportToAdmins($promotion);
        return $this->redirectToRoute('promotion_index', [], Response::HTTP_SEE_OTHER);
    }

    private function handleTemperatureForm(
        Promotion $promotion,
        Request $request,
        EntityManagerInterface $entityManager,
        TemperatureRepository $temperatureRepository,
        EventDispatcherInterface $eventDispatcher,
        array& $viewBag
    ): ?Response {
        $temperaturesForPromotion = $temperatureRepository->getPromotionTemperatureByUser(
            $promotion->getId(),
            $this->getUser()->getId()
        );

        if ($temperaturesForPromotion !== null && count($temperaturesForPromotion) > 0) {
            return null;
        }

        $temperature = new Temperature();
        $temperatureForm = $this->createForm(TemperatureType::class);
        $temperatureForm->handleRequest($request);

        if ($temperatureForm->isSubmitted() && $temperatureForm->isValid()) {
            $temperature->setPromotion($promotion);
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
                    $promotion,
                ),
                'badge.trigger'
            );

            $eventDispatcher->dispatch(new TemperatureAddedEvent($promotion), 'temperature.added');

            return $this->redirectToRoute(
                'promotion_show',
                [
                    'id' => $promotion->getId(),
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        $viewBag['temperature_form'] = $temperatureForm->createView();
        return null;
    }

    /**
     * @Route("/{id}/edit", name="promotion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="promotion_delete", methods={"POST"})
     */
    public function delete(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($promotion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('promotion_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/{id}/savePromotion", name="promotion_add_to_favori", methods={"POST"})
     */
    public function saveToFavorite(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $user->addSavedPromotion($promotion);
        $entityManager->flush();

        return $this->redirectToRoute('promotion_index', [], Response::HTTP_SEE_OTHER);
    }


}
