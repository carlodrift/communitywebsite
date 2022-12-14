<?php

namespace App\Controller;

use App\Repository\DailyClipRepository;
use App\Service\ArticleDisplay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        ArticleDisplay      $articleDisplay,
        DailyClipRepository $dailyClipRepository
    ): Response
    {
        return $this->render('home.html.twig', [
            'articles' => $articleDisplay->getDisplayedArticles(true, 2),
            'dailyclip' => $dailyClipRepository->findAll()[0]->getLink() ?? null,
            'error' => null,
        ]);
    }
}
