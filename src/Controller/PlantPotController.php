<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\PlantPot;
use App\Form\PlantPotType;
use App\Repository\PlantPotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/pot')]
class PlantPotController extends AbstractController
{
#[Route('/list', name: 'app_pot_list')]
public function list(PlantPotRepository $plantPotRepository):Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    $pots = $plantPotRepository->findAll();
    return $this->render('pot/pots.html.twig',["pots"=>$pots]);
}
#[Route('/new', name: 'app_new')]
public function new(Request $request, EntityManagerInterface $entityManager):Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    $pot = new PlantPot();
    $form = $this->createForm(PlantPotType::class, $pot);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid())
    {
        $entityManager->persist($pot);
        $entityManager->flush();
        return new Response('New pot has been added!');
    }
    return $this->render('pot/new.html.twig', ['form'=>$form]);
}
#[Route('/edit/{id}', name: "app_pot_edit")]
public function edit(PlantPot $plantPot, Request $request, EntityManagerInterface $entityManager):Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    $form = $this->createForm(PlantPotType::class, $plantPot);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid())
    {
        $entityManager->persist($plantPot);
        $entityManager->flush();
        return new Response('New pot has been added!');
    }
    return $this->render('pot/new.html.twig', ['form'=>$form]);
}
}