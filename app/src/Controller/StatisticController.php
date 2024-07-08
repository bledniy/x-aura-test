<?php

namespace App\Controller;

use App\Repository\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatisticController extends AbstractController
{
    #[Route('/statistics', name: 'app_statistics_index')]
    public function index(FeedbackRepository $feedbackRepository): Response
    {
        $resumesWithFeedbacks = $feedbackRepository->findTopRatedResumeList();

        return $this->render('statistics/index.html.twig', [
            'resumesWithFeedbacks' => $resumesWithFeedbacks,
        ]);
    }
}
