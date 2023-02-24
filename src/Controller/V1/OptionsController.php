<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\OptionRepository;
use App\Enum\Opencart;

class OptionsController extends AbstractController
{

    private HttpClientInterface $client;
    private OptionRepository $optionRepository;

    public function __construct
    (
        HttpClientInterface $client,
        OptionRepository $optionRepository
    )
    {
        $this->client = $client;
        $this->optionRepository = $optionRepository;
    }

    #[Route('/v1/options', name: 'app_v1_options')]
    public function index(): Response
    {

        $options = $this->optionRepository
                         ->getAllOptionForOC();
             
        dd($this->sendOptions($options));
        return $this->render('v1/options/index.html.twig', [
            'controller_name' => 'OptionsController',
        ]);
    }

    private function sendOptions($options)
    {
        $response = $this->client->request('POST', Opencart::$URL .'/'. Opencart::$GET_OPTIONS, [
            'headers' => ['X-OC-RESTADMIN-ID' => Opencart::$TOKEN_API,
                          'Content-Type'      => 'application/json'],
            'body'    => json_encode($options)
        ]); 
        
        return json_decode(($response->getContent()),true);
    }
}
