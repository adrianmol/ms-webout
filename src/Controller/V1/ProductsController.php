<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\ProductsRepository;
use Enum\Opencart;

class ProductsController extends AbstractController
{

    private HttpClientInterface $client;
    private ProductsRepository $productsRepository;

    public function __construct
    (
        HttpClientInterface $client,
        ProductsRepository $productsRepository
    )
    {
        $this->client = $client;
        $this->productsRepository = $productsRepository;
    }

    #[Route('/v1/products', name: 'app_v1_products')]
    public function index(): Response
    {

        $products = $this->productsRepository
                         ->getAllProductsFromOC();

        dd($this->sendProducts($products));

        return $this->render('v1/products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    private function sendProducts($products)
    {
        $response = $this->client->request('POST', Opencart::$URL .'/'. Opencart::$GET_PRODUCTS, [
            'headers' => ['X-OC-RESTADMIN-ID' => Opencart::$TOKEN_API,
                          'Content-Type'      => 'application/json'],
            'body'    => json_encode($products)
        ]); 
        dd($response->getContent());
        return json_decode(($response->getContent()),true);
    }
}
