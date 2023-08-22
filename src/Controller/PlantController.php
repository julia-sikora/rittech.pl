<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlantController extends AbstractController
{
    #[Route('/plants', name: 'app_plants')]
    public function plants(): Response
    {
        return new Response(' ');
    }
}
