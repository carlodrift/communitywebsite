<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use App\Repository\UserSecurityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        UserSecurityRepository $userRepository,
        ContactRepository      $contactRepository,
        Request                $request,
        UserInterface          $user = null
    ): Response
    {

        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $contact = $form->getData();

            if ($user) {
                $user = $user->getUserIdentifier();
                $author = $userRepository->findOneBy(['username' => $user]);
                $contact->setAuthor($author->getId());
            }

            $contact->setContent(substr($contact->getContent(), 0, 4000));


            $contactRepository->save($contact, true);

            return $this->redirectToRoute('app_contact', ['success' => true]);
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
            'error' => null,
            'success' => $request->query->get('success'),
        ]);
    }
}
