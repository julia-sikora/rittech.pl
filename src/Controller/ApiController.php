<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Plant;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/plant/{id<\d+>}', name: 'app_api_plant')]
    public function apiPlant(Request $request, Plant $plant, LoggerInterface $logger): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $watering = null;
        if ($plant->getWaterings()->last() != false) {
            $watering = $plant->getWaterings()->last()->getDate();
        }
        $array = [
            'id' => $plant->getId(),
            'species' => $plant->getSpecies(),
            'variety' => $plant->getVariety(),
            'date' => $plant->getDateOfPurchase(),
            'last-watering' => $watering
        ];

        $logger->error('Ip: {userIp} , User {userId} retrieved information about {plantId}',
            [
                'userId' => $this->getUser()?->getUserIdentifier(),
                'userIp' => $request->getClientIp(),
                'plantId' => $plant->getId()
            ]
        );

        return new JsonResponse($array);
    }
}
