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

class PlantController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/new', name: 'app_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plant = new Plant();
        $form = $this->createForm(PlantType::class, $plant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plant);
            $entityManager->flush();
            return new Response('New plant had been added!');
        }
        return $this->render('new.html.twig', ['form' => $form]);
    }

    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(Plant $plant, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlantType::class, $plant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plant);
            $entityManager->flush();
            return new Response('Plant had been edited!');
        }
        return $this->render('new.html.twig', ['form' => $form]);
    }

    #[Route('/plants', name: 'app_plants')]
    public function plants(PlantRepository $plantRepository): Response
    {
        return $this->render('plants.html.twig',
            ['plants' => $plantRepository->findAll()]);
    }

    #[Route('/plant/{id}', name: 'app_plant')]
    public function plant($id, PlantRepository $plantRepository): Response
    {
        return $this->render('plant.html.twig',
            ['plant' => $plantRepository->find($id)]);
    }

    #[Route('/list/{id}', name: 'app_list')]
    public function list($id, PlantRepository $plantRepository): Response
    {
        return $this->render('list.html.twig',
            ['plant' => $plantRepository->find($id)]);
    }

    #[Route('/water/{id}', name: 'app_water')]
    public function water(PlantService $plantService, Plant $plant): Response
    {
        $plantService->water($plant);
        return $this->redirectToRoute("app_plants");
    }
}
