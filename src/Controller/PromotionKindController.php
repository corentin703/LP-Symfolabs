<?php

namespace App\Controller;

use App\Entity\PromotionKind;
use App\Form\PromotionKindType;
use App\Repository\PromotionKindRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/promotionKind")
 */
class PromotionKindController extends AbstractController
{
    /**
     * @Route("/", name="promotion_kind_index", methods={"GET"})
     */
    public function index(PromotionKindRepository $promotionKindRepository): Response
    {
        return $this->render('promotion_kind/index.html.twig', [
            'promotion_kinds' => $promotionKindRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="promotion_kind_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotionKind = new PromotionKind();
        $form = $this->createForm(PromotionKindType::class, $promotionKind);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotionKind);
            $entityManager->flush();

            return $this->redirectToRoute('promotion_kind_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promotion_kind/new.html.twig', [
            'promotion_kind' => $promotionKind,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="promotion_kind_show", methods={"GET"})
     */
    public function show(PromotionKind $promotionKind): Response
    {
        return $this->render('promotion_kind/show.html.twig', [
            'promotion_kind' => $promotionKind,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="promotion_kind_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PromotionKind $promotionKind, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromotionKindType::class, $promotionKind);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('promotion_kind_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promotion_kind/edit.html.twig', [
            'promotion_kind' => $promotionKind,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="promotion_kind_delete", methods={"POST"})
     */
    public function delete(Request $request, PromotionKind $promotionKind, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotionKind->getId(), $request->request->get('_token'))) {
            $entityManager->remove($promotionKind);
            $entityManager->flush();
        }

        return $this->redirectToRoute('promotion_kind_index', [], Response::HTTP_SEE_OTHER);
    }
}
