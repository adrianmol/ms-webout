<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\ManufacturerRepository;


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

        // $keyword = $this->client->query->get('q') ?? '';
        // $offset = $this->client->query->get('offset')?? 0;
        // $limit = $this->client->query->get('limit') ?? 10;
        
        $data = $this->manufacturerRepository
        ->findAll();
        return $this->json($data);
    }
}
