<?php

namespace App\Controller\V1;

use App\Entity\Orders;
use App\Entity\OrderProducts;
use App\Entity\OrderTotals;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\OrdersRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Enum\Opencart;


class OrdersController extends AbstractController
{

    private HttpClientInterface $client;
    private OrdersRepository $ordersRepository;
    private ManagerRegistry $doctrine;

    public function __construct
    (
        HttpClientInterface $client,
        OrdersRepository $ordersRepository,
        ManagerRegistry $doctrine
    )
    {
        $this->client = $client;
        $this->ordersRepository = $ordersRepository;
        $this->doctrine = $doctrine;
    }

    #[Route('/v1/orders', name: 'app_v1_orders')]
    public function index(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $orders = $this->getOrders();

        if(empty($orders['data'])){
            return $this->json(['message' => 'done']);
        }

        foreach($orders['data'] as $oc_order){

            $eshop_order_id = $oc_order['order_id'];
                
            $order = new Orders();

            $exist_order = $this->ordersRepository->findOneBy(['eshop_order_id' => $eshop_order_id]);

            if(empty($exist_order)){
                
                $date_added = new \DateTime($oc_order['date_added']);
                $date_modified = new \DateTime($oc_order['date_modified']);

                $order->setEshopOrderId($oc_order['order_id'])
                      ->setStoreId($oc_order['store_id'])
                      ->setStoreName($oc_order['store_name'])
                      ->setStoreUrl($oc_order['store_url'])
                      ->setCustomerId($oc_order['customer_id'])
                      ->setCustomerGroupId($oc_order['customer_group_id'])
                      ->setFirstName($oc_order['firstname'])
                      ->setLastName($oc_order['lastname'])
                      ->setEmail(strtolower(trim($oc_order['email'])))
                      ->setTelephone(preg_replace('/[^0-9]/', '', $oc_order['telephone']))
                      ->setPaymentFirstname($oc_order['payment_firstname'])
                      ->setPaymentLastname($oc_order['payment_lastname'])
                      ->setPaymentCompany($oc_order['payment_company'])
                      ->setPaymentAddress($oc_order['payment_address_1'])
                      ->setPaymentCity($oc_order['payment_city'])
                      ->setPaymentPostcode(preg_replace('/[^0-9]/', '', $oc_order['payment_postcode']))
                      ->setPaymentCountry($oc_order['payment_country'])
                      ->setPaymentCountryId($oc_order['payment_country_id'])
                      ->setPaymentZone($oc_order['payment_zone'])
                      ->setPaymentMethod($oc_order['payment_method'])
                      ->setPaymentCode($oc_order['payment_code'])
                      ->setShippingFirstname($oc_order['shipping_firstname'])
                      ->setShippingLastname($oc_order['shipping_lastname'])
                      ->setShippingCompany($oc_order['shipping_company'])
                      ->setShippingAddress($oc_order['shipping_address_1'])
                      ->setShippingCity($oc_order['shipping_city'])
                      ->setShippingPostcode(preg_replace('/[^0-9]/', '',$oc_order['shipping_postcode']))
                      ->setShippingCountry($oc_order['shipping_country'])
                      ->setShippingCountryId($oc_order['shipping_country_id'])
                      ->setShippingZone($oc_order['shipping_zone'])
                      ->setShippingMethod($oc_order['shipping_method'])
                      ->setShippingCode($oc_order['shipping_code'])
                      ->setComment($oc_order['comment'])
                      ->setTotal($oc_order['total'])
                      ->setOrderStatusId($oc_order['order_status_id'])
                      ->setDateAdded($date_added)
                      ->setDateModified($date_modified)
                      ->setIsInvoiceOrder(false)
                      ;

                    if($oc_order['customer_group_id'] == 2){
                        $invoice_details = json_decode($oc_order['payment_custom_field'], true);

                        $order
                        ->setIsInvoiceOrder(true)
                        ->setVatNumber($invoice_details[1])
                        ->setDoy($invoice_details[2])
                        ->setProfession($invoice_details[3])
                        ;
                    }

                if(isset($oc_order['products'])) foreach($oc_order['products'] as $product){

                    $order_products = new OrderProducts();
                    $order_products->setOrderId($oc_order['order_id'])
                                   ->setProductId($product['product_id'])
                                   ->setName($product['name'])
                                   ->setModel($product['model'])
                                   ->setQuantity($product['quantity'])
                                   ->setPrice($product['price'])
                                   ->setTotal($product['total'])
                                   ->setTax($product['tax'])
                                   ;
                
                    $entityManager->persist($order_products);
                }

                if(isset($oc_order['totals'])) foreach($oc_order['totals'] as $total){
                    $order_total = new OrderTotals();
                    $order_total->setOrderId($oc_order['order_id'])
                                ->setCode($total['code'])
                                ->setTitle($total['title'])
                                ->setValue($total['value'])
                                ->setSortOrder($total['sort_order'])
                                ;
                    
                    $entityManager->persist($order_total);
                }

                $entityManager->persist($order);
            }

        }

        $entityManager->flush();

        return $this->json(['message' => 'done']);
        //return $this->redirectToRoute('admin');
    }

    private function getOrders()
    {
        $response = $this->client->request('GET', Opencart::$URL . '/' . Opencart::$GET_ORDERS, [
            'headers' => [
                'X-OC-RESTADMIN-ID' => Opencart::$TOKEN_API,
                'Content-Type'      => 'application/json'
            ],
        ]);

        return json_decode(($response->getContent()), true);
    }
}
