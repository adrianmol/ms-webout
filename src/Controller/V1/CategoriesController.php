<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/v1/categories', name: 'app_v1_categories')]
    public function index(): Response
    {
        return $this->render('v1/categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }
}
