<?php

namespace App\Controller\Prisma;

use Enum\Prisma;
use App\Entity\Products;
use App\Entity\ProductDescription;

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

            $product_id  = $prisma_product['ItemId'] ?? 0;
            $category_id = $prisma_product['ItemGroupId'] ?? NULL;

            //Init objects <product/productDescription/productCategory>
            //Search for product if exist
            $product = new \App\Entity\Products;
            $productDescription = new \App\Entity\ProductDescription;

            $exist_product = $doctrine->getRepository(\App\Entity\Products::class)->findOneBy(['product_id' => $product_id]);
            $exist_category_id = $doctrine->getRepository(\App\Entity\Categories::class)->findOneBy(['category_id' => $category_id]);

            $model           = $prisma_product['ItemCode'] ?? '';
            $sku             = !empty($prisma_product['ItemBarcode']) ? (string)$prisma_product['ItemBarcode'] : '';
            $quantity        = (int)$prisma_product['ItemStock'] ?? 0;
            $manufacturer_id = (int)$prisma_product['ItemManufacturerId'] ?? 0;
            $wholesale_price = round($prisma_product['ItemWholesale'], 4) ?? 0.0000;
            $price           = round($prisma_product['ItemRetail'], 4) ?? 0.0000;
            $price_with_vat  = round($prisma_product['ItemRetailVat'], 4) ?? 0.000;
            $vat_perc        = round($prisma_product['ItemFPA'], 2) ?? 0.00;
            $weight          = (float)$prisma_product['ItemWeight'] ?? 0.000;
            

            $name        = $prisma_product['ItemDescr'] ?? '';
            $description = !empty($prisma_product['ItemNotes']) ? $prisma_product['ItemNotes'] : '';

            if(!$exist_product){

                $productDescription->setName($name)
                                   ->setDescription($description)
                                   ->setLanguageId(1);

                $product->setProductID($product_id)
                        ->setModel($model)
                        ->setSku($sku)
                        ->setQuantity($quantity)
                        ->setManufacturerId($manufacturer_id)
                        ->setWholesalePrice($wholesale_price)
                        ->setPrice($price)
                        ->setPriceWithVat($price_with_vat)
                        ->setVatPerc($vat_perc)
                        ->setWeight($weight)
                        ->setStatus(1)
                        ->setDateAdded(new \DateTime())
                        ->setDateModified(new \DateTime())
                        ->addProductDescription($productDescription);

                if($exist_category_id) $product->addCategory($exist_category_id);
                
                $entityManager->persist($product);
                $entityManager->persist($productDescription);
                
            }else{

                $exist_product->setModel($model)
                              ->setSku($sku)
                              ->setQuantity($quantity)
                              ->setManufacturerId($manufacturer_id)
                              ->setWholesalePrice($wholesale_price)
                              ->setPrice($price)
                              ->setPriceWithVat($price_with_vat)
                              ->setVatPerc($vat_perc)
                              ->setWeight($weight)
                              ->setDateModified(new \DateTime())
                              ->getProductDescriptions()
                              ->get(0)
                              ->setName($name)
                              ->setDescription($description);

                if($exist_category_id) $exist_product->addCategory($exist_category_id);              
            }
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
