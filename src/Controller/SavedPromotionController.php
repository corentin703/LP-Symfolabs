<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/saved/promotion")
 */
class SavedPromotionController extends AbstractController
{
    /**
     * @Route("/", name="saved_promotion_index")
     */
    public function index(PromotionRepository $promotionRepository, UserRepository $userRepository): Response
    {
//        $userRepository->
        return $this->render('saved_promotion/index.html.twig', [
            'controller_name' => 'SavedPromotionController',
            'savedPromotions' => $this->getUser()->getSavedPromotions(),
            'userID' => $this->getUser()->getUserIdentifier(),
            'size' => count($this->getUser()->getSavedPromotions()),
        ]);
    }

    /**
     * @Route("/{id}", name="saved_promotion_delete", methods={"POST"})
     */
    public function delete(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
//        if ($this->isCsrfTokenValid('delete'.$promotion->getId(), $request->request->get('_token'))) {
//            $entityManager->remove($promotion);
//            $entityManager->flush();
//        }
        $user = $this->getUser();
        $user->removeSavedPromotion($promotion);
        $entityManager->flush();

        return $this->redirectToRoute('saved_promotion_index', [], Response::HTTP_SEE_OTHER);
    }
}
