<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/{name}', name: 'app_service_show')]
    public function showService(string $name): Response
    {
        return $this->render('service/showService.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/go-to-home', name: 'app_go_to_home')]
    public function goToIndex(): Response
    {
        // Redirige vers la méthode "index" du HomeController
        return $this->redirectToRoute('app_home');
    }
}
