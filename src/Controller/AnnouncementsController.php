<?php

namespace App\Controller;

use App\Service\ArticleDisplay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnouncementsController extends AbstractController
{
    #[Route('/announcements', name: 'app_announcements')]
    public function index(ArticleDisplay $articleDisplay, Request $request): Response
    {

        return $this->render('announcements/index.html.twig', [
            'articles' => $articleDisplay->getDisplayedArticlesByCat('annonces', true),
            'notfound' => false,
            'success' => $request->query->get('success'),
            'error' => null,
        ]);
    }
}
