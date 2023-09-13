<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/contact', name: 'app_message_contact')]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $message = new Message();
        $message->setUser($this->getUser());
        $form = $this->createFormBuilder()
            ->add('content', TextareaType::class,
                [
                    'label' => "message.content",
                    'attr' => ['class' => "form-control input-control", 'rows' => 7, 'cols' => 60, 'style' => 'resize: vertical;'],
                    'label_attr' => ['class' => "label-control label-form"]
                ]
            )
            ->add('send', SubmitType::class,
                [
                    'label' => "message.send",
                    'attr' => ['class' => 'button save']
                ]
            )
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setContent($form->get('content')->getData());
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash("success", "flash.message");
            return $this->redirectToRoute('app_homepage');
        }
        return $this->render('contact.html.twig', ['form' => $form, 'title' => 'message.title']);
    }
}
