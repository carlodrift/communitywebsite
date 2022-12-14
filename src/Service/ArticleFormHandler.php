<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use App\Repository\UserSecurityRepository;

class ArticleFormHandler
{

    public function __construct(
        private readonly FileUploader           $fileUploader,
        private readonly UserSecurityRepository $userRepository,
        private readonly ArticleRepository      $articleRepository,
    )
    {
    }


    /**
     * @param $form
     * @param $user
     * @param $directory
     * @param bool $announcement
     * @return void
     */
    public function handle($form, $user, $directory, bool $announcement = false): void
    {
        $article = $form->getData();
        $user = $user->getUserIdentifier();

        $thumbnails = $form->get('thumbnails')->getData();
        if ($thumbnails) {
            $article->setThumbnailsFilename(
                $this->fileUploader->upload($thumbnails, $directory)
            );
        }

        $author = $this->userRepository->findOneBy(['username' => $user]);
        $article->setAuthor($author->getId());
        $article->setContent(substr($article->getContent(), 0, 4000));
        if ($announcement) {
            $article->setCategory("annonces");
        }

        $this->articleRepository->save($article, true);
    }
}
