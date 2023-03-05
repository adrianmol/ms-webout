<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomersController extends AbstractController
{
    #[Route('/v1/customers', name: 'app_v1_customers')]
    public function index(): Response
    {
        return $this->render('v1/customers/index.html.twig', [
            'controller_name' => 'CustomersController',
        ]);
    }
}
