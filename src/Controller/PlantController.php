<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Plant;
use App\Entity\Watering;
use App\Form\PlantType;
use App\Repository\PlantRepository;
use App\Service\PlantService;
use App\Service\WateringService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/plant')]
class PlantController extends AbstractController
{
    #[Route('/new', name: 'app_plant_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $plant = new Plant();
        $form = $this->createForm(PlantType::class, $plant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'flash.plant.add');
            $plant->setUser($this->getUser());
            $entityManager->persist($plant);
            $entityManager->flush();
            $session = $request->getSession();
            $session->set('plant-id', $plant->getId());

            return $this->redirectToRoute('app_plant_list');
        }
        return $this->render('plant/new.html.twig', ['form' => $form, 'title' => 'plant.new.title']);
    }

    #[Route('/edit/{id}', name: 'app_plant_edit')]
    public function edit(Plant $plant, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if ($plant->getUser()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('danger', 'flash.access');
            return $this->redirectToRoute('app_plant_list');
        }

        $form = $this->createForm(PlantType::class, $plant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plant);
            $entityManager->flush();
            $this->addFlash('success', 'flash.plant.edit');
            return $this->redirectToRoute('app_plant_list');
        }
        return $this->render('plant/new.html.twig', ['form' => $form, 'title' => 'plant.edit.title']);
    }

    #[Route('/delete/{id}', name: 'app_plant_delete')]
    public function delete(PlantService $plantService, Plant $plant, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if ($plant->getUser()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('danger', 'flash.access');
            return $this->redirectToRoute('app_plant_list');
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
            $plantService->delete($plant);
            $this->addFlash('success', 'flash.plant.delete');
            return $this->redirectToRoute('app_plant_list');
        }
        return $this->render('plant/delete.html.twig', ['form' => $form, 'plant' => $plant, 'title' => 'plant.delete.title']);
    }

    #[Route('/list', name: 'app_plant_list')]
    public function list(PlantRepository $plantRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $session = $request->getSession();
        $plants = $plantRepository->findBy(['user' => $this->getUser()]);
        $lastPlant = $plantRepository->findOneBy(['id' => $session->get('plant-id'), 'user' => $this->getUser()]);
        return $this->render('plant/list.html.twig', ['plants' => $plants, 'plant' => $lastPlant, 'title' => 'plant.list.title']);
    }

    #[Route('/plant/{id}', name: 'app_plant_plant')]
    public function plant(Plant $plant): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if ($plant->getUser()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('danger', 'flash.access');
            return $this->redirectToRoute('app_plant_list');
        }

        return $this->render('plant/plant.html.twig', ['plant' => $plant, 'title' => 'plant.plant.details.details']);
    }

    #[Route('/waterings/{id}', name: 'app_plant_waterings')]
    public function waterings(Plant $plant): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if ($plant->getUser()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('danger', 'flash.access');
            return $this->redirectToRoute('app_plant_list');
        }

        return $this->render('waterings/list.html.twig', ['plant' => $plant, 'title' => 'water.list.title']);
    }

    #[Route('/watering/delete/{id}', name: 'app_plant_watering_delete')]
    public function wateringDelete(Watering $watering, Request $request, WateringService $wateringService): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if ($watering->getPlant()->getUser()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('danger', 'flash.access');
            return $this->redirectToRoute('app_plant_list');
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
            $wateringService->delete($watering);
            $this->addFlash('success', 'flash.watering.delete');
            return $this->redirectToRoute('app_plant_list', ['id' => $watering->getPlant()->getId()]);
        }
        return $this->render('waterings/delete.html.twig', ['form' => $form, 'watering' => $watering, 'title' => 'water.delete.title']);
    }

    #[Route('/water/{id<\d+>}', name: 'app_plant_water')]
    public function water(WateringService $wateringService, Plant $plant): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        if ($plant->getUser()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('danger', 'flash.access');
            return $this->redirectToRoute('app_plant_list');
        }

        $wateringService->water($plant);

        $this->addFlash('success', 'flash.watering.water');
        return $this->redirectToRoute("app_plant_list");
    }

    #[Route('/water/all', name: 'app_plant_water_all')]
    public function waterAll(WateringService $wateringService): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $wateringService->waterAll($this->getUser());
        $this->addFlash('success', 'flash.watering.water');
        return $this->redirectToRoute("app_plant_list");
    }
}
