<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Plant;
use App\Form\PlantType;
use App\Repository\PlantRepository;
use App\Service\PlantService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            $entityManager->persist($plant);
            $entityManager->flush();
            return new Response('New plant had been added!');
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
            $entityManager->persist($plant);
            $entityManager->flush();
            return new Response('Plant had been edited!');
        }
        return $this->render('plant/new.html.twig', ['form' => $form]);
    }

    #[Route('/plants', name: 'app_plant_plants')]
    public function plants(PlantRepository $plantRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('plant/plants.html.twig',
            ['plants' => $plantRepository->findAll()]);
    }

    #[Route('/plant/{id}', name: 'app_plant_plant')]
    public function plant($id, PlantRepository $plantRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('plant/plant.html.twig',
            ['plant' => $plantRepository->find($id)]);
    }

    #[Route('/list/{id}', name: 'app_plant_list')]
    public function list($id, PlantRepository $plantRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('plant/list.html.twig',
            ['plant' => $plantRepository->find($id)]);
    }

    #[Route('/water/{id}', name: 'app_plant_water')]
    public function water(PlantService $plantService, Plant $plant): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $plantService->water($plant);
        return $this->redirectToRoute("app_plant_plants");
    }
}
