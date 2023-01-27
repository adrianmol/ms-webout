<?php

namespace App\Controller\Prisma;

use Enum\Prisma;
use App\Entity\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Persistence\ManagerRegistry;

class ProductsController extends AbstractController
{


    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/prisma/products', name: 'app_prisma_product')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();

        $products = $this->getProducts();

        foreach($products['StoreDetails'] as $prisma_product){

            $product = new \App\Entity\Products;
            //dd($prisma_product);
            $product->setProductID((string)$prisma_product['ItemId'] ?? 0)
                    ->setModel((string)$prisma_product['ItemCode'] ?? '')
                    ->setSku(!empty($prisma_product['ItemBarcode']) ? (string)$prisma_product['ItemBarcode'] :  '')
                    ->setQuantity((int)$prisma_product['ItemStock'] ?? 0)
                    ->setManufacturerId((int)$prisma_product['ItemManufacturerId'] ?? '')
                    ->setWholesalePrice(round($prisma_product['ItemWholesale'], 4) ?? '')
                    ->setPrice(round($prisma_product['ItemRetail'], 4) ?? 0)
                    ->setPriceWithVat(round($prisma_product['ItemRetailVat'], 4) ?? 0)
                    ->setVatPerc(round($prisma_product['ItemFPA'], 2) ?? 0)
                    ->setWeight((float)$prisma_product['ItemWeight'] ?? 0)
                    ->setStatus(1)
                    ->setDateAdded(new \DateTime())
                    ->setDateModified(new \DateTime());

            $entityManager->persist($product);
            //dd($product);
        }
        
        $entityManager->flush();

        return $this->render('prisma/product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    public function getProducts(){
        // $response = $this->client->request('POST', Prisma::$URL .'/'. Prisma::$GET_PRODUCTS, [
        //     'body' => ['SiteKey'    => Prisma::$SITE_KEY,
        //                'Date'       => '10-1-2022',
        //                'StorageCode'=> '000']
        // ]); 

        //return json_decode(json_encode((array)simplexml_load_string($response->getContent())),true);

        //$this->getParameter('kernel.project_dir').'/public/xml/'
        return json_decode(
            json_encode(
                (array)simplexml_load_string(
                    file_get_contents(
                        $this->getParameter('kernel.project_dir').'/public/xml/'.'GetProducts.xml'
                        )
                    )
                )
                ,true);
    }
}
