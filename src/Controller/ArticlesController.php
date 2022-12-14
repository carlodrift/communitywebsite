<?php

namespace App\Controller;

use App\Form\ArticleFormType;
use App\Service\ArticleDisplay;
use App\Service\ArticleFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(
        ArticleDisplay     $articleDisplay,
        Request            $request,
        ArticleFormHandler $articleFormHandler,
        UserInterface      $user = null
    ): Response
    {

        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $articleFormHandler->handle($form, $user, $this->getParameter('uploads_directory'));

            return $this->redirectToRoute('app_articles', ['success' => true]);
        }

        return $this->render('article/index.html.twig', [
            'articles' => $articleDisplay->getDisplayedArticles(true),
            'articleForm' => $form->createView(),
            'notfound' => false,
            'success' => $request->query->get('success'),
            'error' => null,
        ]);
    }

    #[Route('/articles/{id}', name: 'app_articlesingle')]
    public function single(ArticleDisplay $articleDisplay, Request $request): Response
    {

        if ((int)$request->get('id')) {
            $displayedArticles = $articleDisplay->getDisplayedArticles(false, 1, $request->get('id'));


        } else {
            $displayedArticles = $articleDisplay->getDisplayedArticlesByCat($request->get('id'), false);


        }
        if (!$displayedArticles) {
            return $this->render('article/index.html.twig', [
                'articles' => null,
                'notfound' => true,
                'articleForm' => null,
                'error' => null,
                'success' => null,
            ], new Response('', 404));
        }
        return $this->render('article/index.html.twig', [
            'articles' => $displayedArticles,
            'articleForm' => null,
            'notfound' => false,
            'success' => null,
            'error' => null,
        ]);
    }
}
