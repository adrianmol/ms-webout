<?php

namespace App\Controller\Prisma;

use App\Enum\Prisma;
use App\Entity\Products;
use App\Entity\ProductDescription;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;


class ProductsController extends AbstractController
{

    private $client;
    private $doctrine;

    public function __construct(
        HttpClientInterface $client,
        ManagerRegistry $doctrine
    ) {
        $this->client   = $client;
        $this->doctrine = $doctrine;
    }

    #[Route('/prisma/products', name: 'app_prisma_product')]
    public function index(): Response
    {

        $entityManager = $this->doctrine->getManager();

        $products = $this->getProducts();
        $return_data = array('product_inserted' => 0 , 'product_updated' => 0);

        if(empty($products['StoreDetails']))
            return $this->json($return_data);

        foreach ($products['StoreDetails'] as $prisma_product) {

            if (
                isset($prisma_product['ItemIdMaster'])
                && !empty($prisma_product['ItemIdMaster'])
            ) {
                continue;
            }

            $product_id  = $prisma_product['ItemId'] ?? 0;
            $category_id = $prisma_product['ItemGroupTwoId'] ?? NULL;

            //Init objects <product/productDescription/productCategory>
            //Search for product if exist
            $product = new \App\Entity\Products;
            $productDescription = new \App\Entity\ProductDescription;

            $exist_product = $this->doctrine->getRepository(\App\Entity\Products::class)->findOneBy(['product_id' => $product_id]);
            $exist_category_id = $this->doctrine->getRepository(\App\Entity\Categories::class)->findOneBy(['category_id' => $category_id]);

            $model           = $prisma_product['ItemCode'] ?? '';
            $sku             = !empty($prisma_product['ItemBarcode']) ? (string)$prisma_product['ItemBarcode'] : '';
            $quantity        = (int)$prisma_product['ItemStock'] ?? 0;
            $manufacturer_id = (int)$prisma_product['ItemManufacturerId'] ?? 0;
            $wholesale_price = round($prisma_product['ItemWholesale'], 4) ?? 0.0000;
            $price           = round($prisma_product['ItemRetail'], 4) ?? 0.0000;
            $price_with_vat  = round($prisma_product['ItemRetailVat'], 4) ?? 0.000;
            $vat_perc        = round($prisma_product['ItemFPA'], 2) ?? 0.00;
            $product_discount = round($prisma_product['ItemDiscount'], 4) ?? 0;
            $weight          = (float)$prisma_product['ItemWeight'] ?? 0.000;

            $name        = $prisma_product['ItemDescr'] ?? '';
            $description = !empty($prisma_product['ItemNotes']) ? $prisma_product['ItemNotes'] : '';

            if (!$exist_product) {

                $productDescription->setName($name)
                    ->setDescription($description)
                    ->setLanguageId(2);

                if (!empty($product_discount)) {

                    $discount = new \App\Entity\ProductDiscount;
                    $discount->setPrice($price_with_vat, $product_discount)
                        ->setCustomerGroupId(0)
                        ->setPriority(0)
                        ->setDiscountCode('product_discount');

                    $product->addProductDiscount($discount);
                    $entityManager->persist($discount);
                }

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

                if ($exist_category_id) $product->addCategory($exist_category_id);
                
                $return_data['product_inserted'] += 1;

                $entityManager->persist($product);
                $entityManager->persist($productDescription);
            } else {

                if (!empty($product_discount)) {

                    $exist_discount = $exist_product->getProductDiscounts()
                        ->matching(Criteria::create()
                            ->andWhere(Criteria::expr()->eq('discount_code', 'product_discount')))
                        ->current();

                    if ($exist_discount) {
                        $exist_discount->setPrice($price_with_vat, $product_discount);
                    } else {

                        $discount = new \App\Entity\ProductDiscount;
                        $discount->setPrice($price_with_vat, $product_discount)
                            ->setCustomerGroupId(0)
                            ->setPriority(0)
                            ->setDiscountCode('product_discount');

                        $entityManager->persist($discount);
                        $exist_product->addProductDiscount($discount);
                    }
                } else {
                    $exist_discount = $exist_product->getProductDiscounts()
                        ->matching(Criteria::create()
                            ->andWhere(Criteria::expr()->eq('discount_code', 'product_discount')))
                        ->current();

                    if ($exist_discount) {
                        $entityManager->remove($exist_discount);
                    }
                }

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

                if ($exist_category_id) $exist_product->addCategory($exist_category_id);

                $return_data['product_updated'] += 1;
            }
        }

        $entityManager->flush();
        
        return $this->json($return_data);
    }

    #[Route('/prisma/products/disabled', name: 'app_prisma_product_disabled')]
    public function insertDisabledProducts()
    {
        $entityManager = $this->doctrine->getManager();
        $products = $this->getDisabledProducts();

        $return_data = array('product_disabled' => 0);

        if (!isset($products['StoreItemsNoEshop']) && empty($products['StoreItemsNoEshop'])) {
            return $this->json($return_data);
        }

        foreach ($products['StoreItemsNoEshop'] as $prisma_product) {

            $model = (string)$prisma_product['storecode'];

            $exist_product = $this->doctrine->getRepository(\App\Entity\Products::class)->findOneBy(['model' => $model]);

            $return_data['product_disabled'] += 1;

            if ($exist_product) {
                $exist_product
                    ->setStatus(0)
                    ->setDateModified(new \DateTime());
            }
        }

        $entityManager->flush();

        return $this->json($return_data);
        // return $this->render('prisma/product/index.html.twig', [
        //     'controller_name' => 'ProductController',
        // ]);
    }

    #[Route('/prisma/products/variations', name: 'app_prisma_product_variation')]
    public function insertProductVariation()
    {
        $entityManager = $this->doctrine->getManager();

        $products = $this->getProducts();

        $return_data = array('variation_inserted' => 0 , 'variation_updated' => 0);

        if(empty($products['StoreDetails'])){
            return $this->json($return_data);
        }

        foreach ($products['StoreDetails'] as $prisma_product) {

            if (
                isset($prisma_product['ItemIdMaster'])
                && empty($prisma_product['ItemIdMaster'])
            ) {
                continue;
            }

            $variation_id  = $prisma_product['ItemId'] ?? 0;

            $product_variation = new \App\Entity\ProductVariations;
            $exist_product_variation = $this->doctrine->getRepository(\App\Entity\ProductVariations::class)->findOneBy(['variation_id' => $variation_id]);

            $product_master_id = $prisma_product['ItemIdMaster'] ?? 0;
            $model           = $prisma_product['ItemCode'] ?? '';
            $sku             = !empty($prisma_product['ItemBarcode']) ? (string)$prisma_product['ItemBarcode'] : '';
            $quantity        = (int)$prisma_product['ItemStock'] ?? 0;
            $price_with_vat  = round($prisma_product['ItemRetailVat'], 4) ?? 0.000;
            $weight          = (float)$prisma_product['ItemWeight'] ?? 0.000;

            if (empty($prisma_product['Size'])) continue;

            $option_name = (string)$prisma_product['Size'] ?? NULL;
            $option_value_description = $this->doctrine->getRepository(\App\Entity\OptionValueDescription::class)->findOneBy(['name' => $option_name]);

            if (empty($option_value_description)) {
                $option_value_description = $this->createOptionValueAndDescription($option_name);
            }

            if (!$exist_product_variation) {
                $product_variation->setProductMasterId($product_master_id)
                    ->setVariationId($variation_id)
                    ->setOptionId($option_value_description->getOptionId())
                    ->setOptionValueId($option_value_description->getId())
                    ->setBarcode($model)
                    ->setPrice($price_with_vat)
                    ->setQuantity($quantity);

                $entityManager->persist($product_variation);
                $return_data['variation_inserted']  += 1;

            } else {
                $exist_product_variation->setProductMasterId($product_master_id)
                    ->setVariationId($variation_id)
                    ->setOptionId($option_value_description->getOptionId())
                    ->setOptionValueId($option_value_description->getId())
                    ->setBarcode($model)
                    ->setPrice($price_with_vat)
                    ->setQuantity($quantity);

                $return_data['variation_updated']  += 1;

            }
            
        }

        $entityManager->flush();

        return $this->json($return_data);
        // return $this->render('prisma/product/index.html.twig', [
        //     'controller_name' => 'ProductController',
        // ]);
    }

    #[Route('/prisma/products/customfields', name: 'app_prisma_custom_fields')]
    public function insertCustomFields()
    {
        $entityManager = $this->doctrine->getManager();
        $custom_fields = $this->getCustomFields();

        if (!isset($custom_fields['CustomFields']) && empty($custom_fields['CustomFields'])) {

            return $this->json(['total_custom_fields' => 0]);
        }

        foreach ($custom_fields['CustomFields'] as $field) {

            $product_id = (int)$field['ApoId'];

            $exist_product = $this->doctrine->getRepository(\App\Entity\Products::class)->findOneBy(['product_id' => $product_id]);
            
            if ($exist_product) {

                $sku = isset($field['CustomField_13']) && !empty($field['CustomField_13']) ? (string)$field['CustomField_13'] : ''; //??????.???????????? ?????? ????????????????????
                $mpn = isset($field['CustomField_15']) && !empty($field['CustomField_15']) ? (string)$field['CustomField_15'] : ''; //??????.???????????? ????????????????????????

                $exist_product
                    ->setSku($sku)
                    ->setMpn($mpn);
            }
        }

        $entityManager->flush();

        return $this->json(['total_custom_fields' => count($custom_fields['CustomFields'])]);
        // return $this->render('prisma/product/index.html.twig', [
        //     'controller_name' => 'ProductController',
        // ]);

    }

    private function createOptionValueAndDescription($name)
    {
        $option = new \App\Entity\OptionValue;
        $option_value = new \App\Entity\OptionValueDescription;
        $optionValueRepository = new \App\Repository\OptionValueRepository($this->doctrine);
        $optionValueDescriptionRepository = new \App\Repository\OptionValueDescriptionRepository($this->doctrine);

        $optionValueRepository->save(
            $option->setOptionId(1)->setSortOrder(0),
            true
        );

        $optionValueDescriptionRepository->save(
            $option_value->setId($option->getId())
                ->setLanguageId(2)
                ->setOptionId(1)
                ->setName($name),
            true
        );

        return $option_value;
    }

    private function getDisabledProducts()
    {
        $response = $this->client->request('POST', Prisma::$URL . '/' . Prisma::$GET_DISABLED_PRODUCTS, [
            'body' => [
                'SiteKey'    => Prisma::$SITE_KEY,
                'Date'       => date('m-d-Y H:m', strtotime("-4 hours")),
                'StorageCode' => Prisma::$STORAGE_CODE[0]
            ]
        ]);

        return json_decode(json_encode((array)simplexml_load_string($response->getContent())), true);
    }

    private function getProducts()
    {
        $response = $this->client->request('POST', Prisma::$URL . '/' . Prisma::$GET_PRODUCTS, [
            'body' => [
                'SiteKey'    => Prisma::$SITE_KEY,
                'Date'       => date('m-d-Y H:m', strtotime("-4 hours")),
                'StorageCode' => Prisma::$STORAGE_CODE[0]
            ]
        ]);

        return json_decode(json_encode((array)simplexml_load_string($response->getContent())), true);

        // $this->getParameter('kernel.project_dir').'/public/xml/'
        // return json_decode(
        //     json_encode(
        //         (array)simplexml_load_string(
        //             file_get_contents(
        //                 $this->getParameter('kernel.project_dir') . '/public/xml/' . 'GetProducts.xml'
        //             )
        //         )
        //     ),
        //     true
        // );
    }

    private function getCustomFields()
    {
        $response = $this->client->request('POST', Prisma::$URL . '/' . Prisma::$GET_CUSTOM_FIELDS, [
            'body' => [
                'SiteKey'    => Prisma::$SITE_KEY,
                'Date'       => date('m-d-Y H:m', strtotime("-4 hours")),
                'StorageCode' => Prisma::$STORAGE_CODE[0]
            ]
        ]);

        return json_decode(json_encode((array)simplexml_load_string($response->getContent())), true);
    }
}
