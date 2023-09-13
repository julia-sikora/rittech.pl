<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function admin(MessageRepository $messageRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        } catch (Throwable) {
            $this->addFlash("danger", "flash.access");
            return $this->redirectToRoute('app_homepage');
        }

        $messages = $messageRepository->findAll();
        return $this->render('admin.html.twig', ['title' => 'homepage.admin.title', 'messages' => $messages]);
    }
}
