<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/v1/products', name: 'app_v1_products')]
    public function index(): Response
    {
        return $this->render('v1/products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
}
