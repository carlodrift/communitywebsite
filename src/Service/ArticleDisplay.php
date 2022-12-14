<?php

namespace App\Service;

use App\Entity\DisplayedArticle;
use App\Repository\ArticleRepository;
use App\Repository\UserSecurityRepository;

class ArticleDisplay
{

    public function __construct(
        private readonly ArticleRepository      $articleRepository,
        private readonly UserSecurityRepository $userSecurityRepository,
    )
    {
    }


    /**
     * @param bool|null $short
     * @param int|null $limit
     * @param int|null $id
     * @return array
     */
    public function getDisplayedArticles(bool $short = null, int $limit = null, int $id = null): array
    {
        if (is_null($id)) {
            $articles = $this->articleRepository->findAll();
            $articles = array_reverse($articles);
        } else {
            $article = $this->articleRepository->find($id);
            if ($article) {
                $articles[] = $article;
            } else {
                return [];
            }
        }
        $displayedArticles = $this->convertArticlesToDisplayedArticles($articles, $short);
        if ($limit) {
            $displayedArticles = array_slice($displayedArticles, 0, $limit);
        }
        return $displayedArticles;
    }

    /**
     * @param array $articles
     * @param bool|null $short
     * @return array
     */
    private function convertArticlesToDisplayedArticles(array $articles, bool $short = null): array
    {
        $displayedArticles = [];
        foreach ($articles as $article) {
            $displayedArticle = new DisplayedArticle();
            $author = $this->userSecurityRepository->find($article->getAuthor());
            $displayedArticle->setTitle($article->getTitle(), $short);
            $displayedArticle->setContent($article->getContent(), $short);
            if ($author) {
                $displayedArticle->setAuthor($author->getUsername());
            } else {
                $displayedArticle->setAuthor('Auteur inconnu');
            }
            if ($article->getCategory()) {
                $displayedArticle->setCategory($article->getCategory());
            }
            $displayedArticle->setId($article->getId());
            if ($article->getThumbnailsFileName()) {
                $displayedArticle->setThumbnailsFileName($article->getThumbnailsFileName());
            }
            $displayedArticles[] = $displayedArticle;
        }
        return $displayedArticles;
    }

    /**
     * @param string $cat
     * @param bool $short
     * @param int|null $limit
     * @return array
     */
    public function getDisplayedArticlesByCat(string $cat, bool $short, int $limit = null): array
    {
        $articles = $this->articleRepository->findBy(['category' => $cat]);
        $articles = array_reverse($articles);
        $displayedArticles = $this->convertArticlesToDisplayedArticles($articles, $short);
        if ($limit) {
            $displayedArticles = array_slice($displayedArticles, 0, $limit);
        }
        return $displayedArticles;
    }
}
