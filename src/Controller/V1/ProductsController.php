<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\ProductsRepository;
use App\Repository\ProductVariationsRepository;
use Enum\Opencart;

class ProductsController extends AbstractController
{

    private HttpClientInterface $client;
    private ProductsRepository $productsRepository;
    private ProductVariationsRepository $productVariationsRepository;

    public function __construct(
        HttpClientInterface $client,
        ProductsRepository $productsRepository,
        ProductVariationsRepository $productVariationsRepository
    ) {
        $this->client = $client;
        $this->productsRepository = $productsRepository;
        $this->productVariationsRepository = $productVariationsRepository;
    }

    #[Route('/v1/products', name: 'app_v1_products')]
    public function index(): Response
    {
        $products = $this->prepareProductsData();

        $response = $this->sendProducts($products);

        return $this->json($response);
        // return $this->render('v1/products/index.html.twig', [
        //     'controller_name' => 'ProductsController',
        // ]);
    }

    #[Route('/v1/products/options', name: 'app_v1_products_options')]
    public function sendProductOption(): Response
    {
        $product_variations = $this->prepareProductsOptionsData();
        $response = $this->sendProductsOptions($product_variations);

        return $this->json($response);
    }


    private function sendProducts($products)
    {
        $response = $this->client->request('POST', Opencart::$URL . '/' . Opencart::$GET_PRODUCTS, [
            'headers' => [
                'X-OC-RESTADMIN-ID' => Opencart::$TOKEN_API,
                'Content-Type'      => 'application/json'
            ],
            'body'    => json_encode($products)
        ]);

        return json_decode(($response->getContent()), true);
    }

    private function sendProductsOptions($products_options)
    {
        $response = $this->client->request('POST', Opencart::$URL . '/' . Opencart::$GET_PRODUCTS_OPTIONS, [
            'headers' => [
                'X-OC-RESTADMIN-ID' => Opencart::$TOKEN_API,
                'Content-Type'      => 'application/json'
            ],
            'body'    => json_encode($products_options)
        ]);

        return json_decode(($response->getContent()), true);
    }

    private function prepareProductsData(): array
    {
        $oc_products = array();

        $data = $this->productsRepository
            ->getAllProductsForOC();

        foreach ($data as $product) {

            $categories = array_map(function ($value) {
                return $value['category_id'];
            }, $product['category']);

            $quantity = $this->productVariationsRepository->getTotalQuantity($product['product_id']);

            $product_description = array_map(function ($value) {
                return [
                    'language_id' => $value['language_id'],
                    'name'        => $value['name'],
                    'meta_title'  => $value['name'],
                    'description' => $value['description'],
                    'meta_description' => '',
                    'tag'         => $value['tag'],
                ];
            }, $product['productDescriptions']);

            $oc_products[] = [
                'id'            => $product['product_id'],
                'model'         => $product['model'],
                'sku'           => $product['sku'],
                'mpn'           => $product['mpn'],
                'upc'           => '',
                'ean'           => '',
                'jan'           => '',
                'isbn'          => '',
                'location'      => '',
                'minimum'       => 1,
                'subtract'      => 1,
                'points'        => 0,
                'shipping'      => 1,
                'weight_class_id' => 1,
                'length'        => 0,
                'width'         => 0,
                'height'        => 0,
                'length_class_id' => 1,
                'tax_class_id'  => 1,
                'sort_order'    => 0,
                'discount_price' => '',
                'price'         => $product['price_with_vat'],
                'quantity'      => !empty($quantity) ? $quantity : $product['quantity'],
                'manufacturer_id'   => $product['manufacturer_id'],
                'weight'        => $product['weight'],
                'status'        => $product['status'],
                'product_category'    => $categories,
                'product_description' => $product_description,
                'product_store' => 0
            ];
        }

        return $oc_products;
    }


    private function prepareProductsOptionsData(): array
    {
        $product_variations = $this->productVariationsRepository
            ->getAllProductsVariationsForOC();

        if (empty($product_variations)) {
            return [];
        }

        $product_variations = $this->arrayGroupBy('product_master_id', $product_variations);

        return $product_variations;
    }

    private function arrayGroupBy($key, $data)
    {
        $result = array();

        foreach ($data as $val) {

            if (array_key_exists($key, $val)) {
                if (!isset($result[$val[$key]]['product_option_id'])) {
                    $result[$val[$key]]['product_option_id'] = $val['variation_id'];
                    $result[$val[$key]]['product_id']        = $val['product_master_id'];
                    $result[$val[$key]]['option_id']         = $val['option_id'];
                    $result[$val[$key]]['required']          = 1;
                }
                $result[$val[$key]]['products_option_values'][] = $val;
            }
        }

        return $result;
    }
}
