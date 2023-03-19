<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\ProductsRepository;
use App\Repository\ProductVariationsRepository;
use App\Repository\ProductDiscountRepository;
use App\Enum\Opencart;

class ProductsController extends AbstractController
{

    private HttpClientInterface $client;
    private ProductsRepository $productsRepository;
    private ProductVariationsRepository $productVariationsRepository;
    private ProductDiscountRepository $productDiscountRepository;

    public function __construct(
        HttpClientInterface $client,
        ProductsRepository $productsRepository,
        ProductVariationsRepository $productVariationsRepository,
        ProductDiscountRepository $productDiscountRepository
    ) {
        $this->client = $client;
        $this->productsRepository = $productsRepository;
        $this->productVariationsRepository = $productVariationsRepository;
        $this->productDiscountRepository = $productDiscountRepository;
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

    #[Route('/v1/products/discount', name: 'app_v1_products_options')]
    public function sendProductDiscount(): Response
    {
        $product_discounts = $this->prepareProductsDiscount();
        $response = $this->sendData($product_discounts, Opencart::$GET_PRODUCTS_DISCOUNT);

        return $this->json($response);
    }

    private function sendData($data, $request_url)
    {
        $response = $this->client->request('POST', Opencart::$URL . '/' . $request_url, [
            'headers' => [
                'X-OC-RESTADMIN-ID' => Opencart::$TOKEN_API,
                'Content-Type'      => 'application/json'
            ],
            'body'    => json_encode($data)
        ]);

        return json_decode(($response->getContent()), true);
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
        $get_last_four_hours_products = date('Y-m-d H:m:s', strtotime("-4 hours"));

        $data = $this->productsRepository
            ->getAllProductsForOC(['date_modified' => $get_last_four_hours_products]);

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
                'supplier_quantity' => $product['supplier_quantity'] ?? '',
                'manufacturer_id'   => $product['manufacturer_id'],
                'weight'        => $product['weight'],
                'status'        => $product['status'],
                'date_added'    => $product['date_added']->format('Y-m-d H:i:s'),
                'date_modified' => $product['date_modified']->format('Y-m-d H:i:s'),
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

    private function prepareProductsDiscount(): array
    {
        $discounts = $this->productDiscountRepository
            ->getDiscounts();

        if (empty($discounts)) {
            return [];
        }
        //dd($discounts);
        $oc_discounts = array();
        foreach ($discounts as $discount){

            $oc_discounts[] = [
                'product_id'    => $discount->getProduct()->getProductId(),
                'customer_group_id' => $discount->getCustomerGroupId(),
                'priority'      => $discount->getPriority(),
                'price'         => $discount->getPrice(),

            ];
        }
        return $oc_discounts;
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
