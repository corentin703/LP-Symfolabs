<?php

namespace App\Controller;

use App\Entity\Promotion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SavedPromotionController extends AbstractController
{
    /**
     * @Route("/saved/promotion", name="saved_promotion_index")
     */
    public function index(): Response
    {
        return $this->render('saved_promotion/index.html.twig', [
            'controller_name' => 'SavedPromotionController',
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
