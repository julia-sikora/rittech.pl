<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PlantPot;
use App\Form\PlantPotType;
use App\Repository\PlantPotRepository;
use App\Service\PlantPotService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pot')]
class PlantPotController extends AbstractController
{
    #[Route('/list', name: 'app_pot_list')]
    public function list(PlantPotRepository $plantPotRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $pots = $plantPotRepository->findBy(['user' => $this->getUser()]);
        return $this->render('pot/pots.html.twig', ["pots" => $pots, 'title' => 'pot.list.title']);
    }

    #[Route('/new', name: 'app_pot_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $pot = new PlantPot();
        $form = $this->createForm(PlantPotType::class, $pot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pot->setUser($this->getUser());
            $entityManager->persist($pot);
            $entityManager->flush();
            $this->addFlash('success', 'flash.pot.add');
            return $this->redirectToRoute('app_pot_list');
        }
        return $this->render('pot/new.html.twig', ['form' => $form, 'title' => 'pot.new.title']);
    }

    #[Route('/edit/{id}', name: "app_pot_edit")]
    public function edit(PlantPot $plantPot, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if ($plantPot->getUser()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('danger', 'flash.access');
            return $this->redirectToRoute('app_pot_list');
        }

        $form = $this->createForm(PlantPotType::class, $plantPot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plantPot);
            $entityManager->flush();
            $this->addFlash('success', 'flash.pot.edit');
            return $this->redirectToRoute('app_pot_list');
        }
        return $this->render('pot/new.html.twig', ['form' => $form, 'title' => 'pot.edit.title']);
    }

    #[Route('/delete/{id}', name: 'app_pot_delete')]
    public function delete(PlantPotService $plantPotService, PlantPot $plantPot, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if ($plantPot->getUser()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('danger', 'flash.access');
            return $this->redirectToRoute('app_pot_list');
        }

        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class,
                [
                    'label' => "pot.fields.delete",
                    'attr' => ['class' => 'delete']
                ]
            )
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plantPotService->delete($plantPot);
            $this->addFlash('success', 'flash.pot.delete');
            return $this->redirectToRoute('app_pot_list');
        }
        return $this->render('pot/delete.html.twig', ['form' => $form, 'pot' => $plantPot, 'title' => 'pot.delete.title']);
    }
}