<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Promotion;
use App\Form\CommentType;
use App\Form\PromotionType;
use App\Repository\CommentRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotion->setCreatedAt(new \DateTime('now'));
            $promotion->setAuthor($this->getUser());
            $entityManager->persist($promotion);
            $entityManager->flush();

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
        EntityManagerInterface $entityManager
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

                return $this->redirectToRoute(
                    'promotion_show',
                    [
                        'id' => $promotion->getId(),
                    ],
                    Response::HTTP_SEE_OTHER
                );
            }

            $viewBag['comment_form'] = $commentForm->createView();
        }

        return $this->render('promotion/show.html.twig', $viewBag);
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
}
