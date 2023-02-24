<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\ManufacturerRepository;
use App\Enum\Opencart;

class ManufacturersController extends AbstractController
{

   

    private HttpClientInterface $client;
    private ManufacturerRepository $manufacturerRepository;

    public function __construct
    (
        HttpClientInterface $client,
        ManufacturerRepository $manufacturerRepository
    )
    {
        $this->client = $client;
        $this->manufacturerRepository = $manufacturerRepository;
    }

    #[Route(path: '/v1/manufacturers', name: 'app_v1_manufacturers_all', methods: ['GET'])]
    function all(): Response
    {

        $manufacturers = $this->manufacturerRepository
                        ->findAll();

        $response = $this->sendManufacturers($manufacturers);

        return $this->json($response);
    }

    private function sendManufacturers($manufacturers)
    {
        $response = $this->client->request('POST', Opencart::$URL .'/'. Opencart::$GET_MANUFACTURER, [
            'headers' => ['X-OC-RESTADMIN-ID' => Opencart::$TOKEN_API,
                          'Content-Type'      => 'application/json'],
            'body'    => json_encode($manufacturers)
        ]); 

        return json_decode(($response->getContent()),true);
    }
}
