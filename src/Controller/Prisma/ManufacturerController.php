<?php

namespace App\Controller\Prisma;

use App\Enum\Prisma;
use App\Entity\Manufacturer;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class ManufacturerController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/prisma/manufacturer', name: 'app_prisma_manufacturer')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {  
        $entityManager = $doctrine->getManager();
        $prisma_manufacturers = $this->getManufacturer();


        $user = new User();
        $user->setEmail('adrian.mol@hotmail.com')->setRoles(['ROLE_ADMIN'])->setPassword('adrian1!');
        $entityManager->persist($user);
        $data_response = array();
        
        foreach($prisma_manufacturers['ManufacturerDetails'] as $prisma_manufacturer){

            $id = $prisma_manufacturer['ManufacturerID'];
            $name = $prisma_manufacturer['ManufacturerName'];

            $Manufacturer = new \App\Entity\Manufacturer;
            $exist_manufacturer = $doctrine->getRepository(\App\Entity\Manufacturer::class)->findOneBy(['manufacturer_id' => $id]);

            if(!$exist_manufacturer){
                
                $Manufacturer->setName($name)
                ->setManufacturerId($id)
                ->setSortOrder(0)
                ->setStatus(1)
                ->setDateAdded(new \DateTime())
                ->setDateModified(new \DateTime())
                ->setImage('');

                $data_response['inserted'][] = $Manufacturer->getManufacturerId();

                $entityManager->persist($Manufacturer);

            }else{

                $exist_manufacturer
                ->setName($name)
                ->setDateModified(new \DateTime());
                
                $data_response['updated'][] = [
                    'manufacturer_id'   => $exist_manufacturer->getManufacturerId(),
                    'name'              => $exist_manufacturer->getName()
                ];
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute(
        'admin',
        ['crudAction'=>'index',
        'crudControllerFqcn'=>'App\Controller\Admin\ManufacturerCrudController']);
        
        // return $this->render('prisma/manufacturer/index.html.twig', [
        //     'data' => $data_response,
        // ]);
    }

    public function getManufacturer(){
        $response = $this->client->request('POST', Prisma::$URL .'/'. Prisma::$GET_MANUFACTURER, [
            'body' => ['SiteKey' => Prisma::$SITE_KEY],
        ]); 

        return json_decode(json_encode((array)simplexml_load_string($response->getContent())),true);
    }
}
