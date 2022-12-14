<?php

namespace App\Controller;

use App\Entity\DisplayedArticle;
use App\Form\AnnouncementFormType;
use App\Form\DailyClipFormType;
use App\Repository\ArticleRepository;
use App\Repository\ContactRepository;
use App\Repository\DailyClipRepository;
use App\Repository\UserSecurityRepository;
use App\Service\ArticleFormHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends AbstractController
{
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin', name: 'app_admin')]
    public function index(
        UserSecurityRepository $userRepository,
        ArticleRepository      $articleRepository,
        ContactRepository      $contactRepository,
        DailyClipRepository    $dailyClipRepository,
        Request                $request,
        ArticleFormHandler     $articleFormHandler,
        UserInterface          $user = null
    ): Response
    {

        $form = $this->createForm(AnnouncementFormType::class);
        $dailyclipForm = $this->createForm(DailyClipFormType::class);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $articleFormHandler->handle($form, $user, $this->getParameter('uploads_directory'), true);

            return $this->redirectToRoute('app_admin');
        }

        $dailyclipForm->handleRequest($request);
        if ($dailyclipForm->isSubmitted() && $dailyclipForm->isValid()) {
            $dailyClipRepository->clear(true);
            $dailyClipRepository->save($dailyclipForm->getData(), true);

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/index.html.twig', [
            'articleForm' => $form->createView(),
            'dailyclipForm' => $dailyclipForm->createView(),
            'articles' => $articleRepository->findAll(),
            'users' => $userRepository->findAll(),
            'contacts' => $contactRepository->findAll(),
            'notfound' => false,
            'success' => $request->query->get('success'),
            'error' => null,
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin/addadmin/{id}', name: 'app_admin_addadmin')]
    public function addAdmin(UserSecurityRepository $userRepository, $id): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        $user->addRole("ROLE_ADMIN");
        $userRepository->save($user, true);
        return $this->redirectToRoute('app_admin');
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin/removeadmin/{id}', name: 'app_admin_removeadmin')]
    public function removeAdmin(UserSecurityRepository $userRepository, $id): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        $user->removeRole("ROLE_ADMIN");
        $userRepository->save($user, true);
        return $this->redirectToRoute('app_admin');
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin/deleteuser/{id}', name: 'app_admin_deleteuser')]
    public function deleteUser(UserSecurityRepository $userRepository, $id): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        $userRepository->remove($user, true);

        return $this->redirectToRoute('app_admin');
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin/deletearticle/{id}', name: 'app_admin_deletearticle')]
    public function deleteArticle(ArticleRepository $articleRepository, $id): Response
    {
        $article = $articleRepository->findOneBy(['id' => $id]);
        $articleRepository->remove($article, true);

        return $this->redirectToRoute('app_admin');
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin/contact/remove/{id}', name: 'app_admin_contact_remove')]
    public function removeContact(ContactRepository $contactRepository, $id): Response
    {
        $contact = $contactRepository->findOneBy(['id' => $id]);
        $contactRepository->remove($contact, true);

        return $this->redirectToRoute('app_admin');
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin/contact/{id}', name: 'app_admin_contactmessage')]
    public function contactMessage(
        UserSecurityRepository $userRepository,
        ContactRepository      $contactRepository,
                               $id
    ): Response
    {
        $contact = $contactRepository->findOneBy(['id' => $id]);
        $displayedContact = new DisplayedArticle();
        if ($contact) {
            $displayedContact->setTitle($contact->getTitle());
            $displayedContact->setContent($contact->getContent());
            if ($contact->getAuthor()) {
                $author = $userRepository->find($contact->getAuthor());
                if ($author) {
                    $displayedContact->setAuthor($author->getUsername());
                } else {
                    $displayedContact->setAuthor('Compte supprimé');
                }
            } else {
                $displayedContact->setAuthor('Anonyme');
            }
        }


        return $this->render('admin/contact.html.twig', [
            'contact' => $displayedContact,
            'error' => null,
            'notfound' => $contact === null,
        ]);
    }
}
