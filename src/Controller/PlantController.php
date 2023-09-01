<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Plant;
use App\Entity\Watering;
use App\Form\PlantType;
use App\Repository\PlantRepository;
use App\Repository\WateringRepository;
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
    #[Route('/', name: 'app_plant_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('plant/index.html.twig');
    }

    #[Route('/new', name: 'app_plant_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $plant = new Plant();
        $form = $this->createForm(PlantType::class, $plant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'New plant has been added!');
            $entityManager->persist($plant);
            $entityManager->flush();
            $session = $request->getSession();
            $session->set('plant-id', $plant->getId());

            return $this->redirectToRoute('app_plant_list');
        }
        return $this->render('plant/new.html.twig', ['form' => $form]);
    }

    #[Route('/edit/{id}', name: 'app_plant_edit')]
    public function edit(Plant $plant, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        //mozemy wyszukac ktory to plant po {id}
        //ale symfony samo to robi po dopasowaniu {id} do "Plant $plant"
        $form = $this->createForm(PlantType::class, $plant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Plant has been edited!');
            $entityManager->persist($plant);
            $entityManager->flush();
            return $this->redirectToRoute('app_plant_list');
        }
        return $this->render('plant/new.html.twig', ['form' => $form]);
    }

    #[Route('/delete/{id}', name: 'app_plant_delete')]
    public function delete(PlantService $plantService, Plant $plant, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plantService->delete($plant);
            $this->addFlash('success', 'Plant has been deleted!');
            return $this->redirectToRoute('app_plant_list');
        }
        return $this->render('plant/delete.html.twig', ['form' => $form, 'plant' => $plant]);
    }

    #[Route('/list', name: 'app_plant_list')]
    public function list(PlantRepository $plantRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $session = $request->getSession();
        $newPlant = null;
        $plantId = $session->get('plant-id');
        if ($plantId !== null) {
            $newPlant = $plantRepository->find($plantId);
        }
        return $this->render('plant/list.html.twig',
            ['plants' => $plantRepository->findAll(), 'plant' => $newPlant]);
    }

    #[Route('/plant/{id}', name: 'app_plant_plant')]
    public function plant($id, PlantRepository $plantRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('plant/plant.html.twig',
            ['plant' => $plantRepository->find($id)]);
    }

    #[Route('/waterings/{id}', name: 'app_plant_waterings')]
    public function waterings($id, PlantRepository $plantRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('waterings/list.html.twig',
            ['plant' => $plantRepository->find($id)]);
    }

    #[Route('/watering/delete/{id}', name: 'app_plant_watering_delete')]
    public function wateringDelete(Watering $watering, Request $request, WateringService $wateringService): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $wateringService->delete($watering);
            $this->addFlash('success', 'Watering has been deleted!');
            return $this->redirectToRoute('app_plant_waterings', ['id'=>$watering->getPlant()->getId()]);
        }
        return $this->render('waterings/delete.html.twig', ['form' => $form, 'watering' => $watering]);
    }
    #[Route('/water/{id<\d+>}', name: 'app_plant_water')]
    public function water(WateringService $wateringService, Plant $plant): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $wateringService->water($plant);

        $this->addFlash('success', 'Plant has been watered!');
        return $this->redirectToRoute("app_plant_list");
    }
    #[Route('/water/all', name: 'app_plant_water_all')]
    public function waterAll(WateringService $wateringService): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $wateringService->waterAll();

        $this->addFlash('success', 'Plant has been watered!');
        return $this->redirectToRoute("app_plant_list");
    }
}
