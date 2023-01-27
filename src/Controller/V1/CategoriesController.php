<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\CategoriesRepository;
use Enum\Opencart;

class CategoriesController extends AbstractController
{

    private HttpClientInterface $client;
    private CategoriesRepository $categoriesRepository;

    public function __construct
    (
        HttpClientInterface $client,
        CategoriesRepository $categoriesRepository
    )
    {
        $this->client = $client;
        $this->categoriesRepository = $categoriesRepository;
    }

    #[Route(path: '/v1/categories', name: 'app_v1_categories_all', methods: ['GET'])]
    function all(): Response
    {

        $categories = $this->categoriesRepository
                        ->getAllElementsForSend(['status' => 1 , 'eshop_status' => 1]);
        $response = $this->sendCategories($categories);

        return $this->json($response);
    }

    private function sendCategories($categories)
    {
        $response = $this->client->request('POST', Opencart::$URL .'/'. Opencart::$GET_CATEGORIES, [
            'headers' => ['X-OC-RESTADMIN-ID' => Opencart::$TOKEN_API,
                          'Content-Type'      => 'application/json'],
            'body'    => json_encode($categories)
        ]); 

        return json_decode(($response->getContent()),true);
    }
}
