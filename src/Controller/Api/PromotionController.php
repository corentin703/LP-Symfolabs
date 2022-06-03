<?php

namespace App\Controller\Api;

use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends AbstractController
{
    /**
     * @Route("/api/promotion", name="api_promotion")
     */
    public function index(PromotionRepository $promotionRepository): Response
    {
        return $this->json($promotionRepository->findAll());
    }
}
