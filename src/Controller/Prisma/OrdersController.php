<?php

namespace App\Controller\Prisma;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\OrdersRepository;
use App\Enum\Prisma;

class OrdersController extends AbstractController
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

    #[Route('/prisma/orders', name: 'app_prisma_orders')]
    public function index(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $orders = new OrdersRepository($this->doctrine);

        $orders_data = $orders->findAllOrdersThatDoentSentToErp();

        foreach ($orders_data as $order) {

            $prepare_customer_data = $this->prepareCustomer($order);
            $prisma_customer = $this->insertCustomer($prepare_customer_data);

            $order->products = $this->doctrine->getRepository(\App\Entity\OrderProducts::class)->findAll(['order_id' => $order->getEshopOrderId()]);
            $order->totals   = $this->doctrine->getRepository(\App\Entity\OrderTotals::class)->findAll(['order_id' => $order->getEshopOrderId()]);

            $prepare_order_data = $this->prepareOrder($order, $order->products);
            $prisma_order = $this->insertOrder($prepare_order_data);
            
            if(isset($prisma_order[0])){
                $order->setErpOrderId($prisma_order[0]);
                $entityManager->flush();
            }
            
            dd($prisma_order);
 
        
        }
        
        return $this->render('prisma/orders/index.html.twig', [
            'controller_name' => 'OrdersController',
        ]);
    }


    private function prepareCustomer($order) 
    {

        $customer_name = $order->isIsInvoiceOrder() ? $order->getPaymentCompany() : $order->getFirstName() . ' ' . $order->getLastName();

        return [
            'CustUsername' 	=> $order->getEmail(),
            'CustName' 	   	=> $customer_name,
            'CustAfm' 	   	=> $order->isIsInvoiceOrder() ? $order->getVatNumber() : '',
            'CustFpa' 	   	=> '01',
            'ShippingCity' 	=> $order->getPaymentCity(),
            'CustCity' 		=> $order->getPaymentCity(),
            'CustAddress' 	=> $order->getPaymentAddress(),
            'ShippingAddress'=>$order->getPaymentAddress(),
            'CustZip' 		=> $order->getPaymentPostCode(),
            'ShippingZip' 	=> $order->getPaymentPostCode(),
            'CustEmail'     => $order->getEmail(),
            'CustMobile' 	=> $order->getTelephone(),
            'CustTel' 		=> '',
            'CustDOY' 		=> $order->isIsInvoiceOrder() ? $order->getDoy() : '',
            'CustPricelist' => '02',
            'CustFax' 		=> '',
            'CustBusiness' 	=> $order->isIsInvoiceOrder() ? $order->getProfession() : '',
            'CustKepyo' 	=> $order->isIsInvoiceOrder() ? '01' : '05',
            'CustCountryCode'=> 'EL',
            'CustPaymentMethodCode' => '05'
        ];
    }

    private function prepareOrder(&$order, &$products)
    {

        $customer_name = $order->isIsInvoiceOrder() ? $order->getPaymentCompany() : $order->getFirstName() . ' ' . $order->getLastName();
        $prisma_products = $this->prepareProducts($products);

        return [
            'CustUsername'  => $order->getEmail(),
            'OrderNo'       => $order->getEshopOrderId(),
            'StorageCode'   => '000',
            'SeiraPar'      => 'A',
            'TroposPliromis' => $this->getPaymentMethodsForPrisma($order->getPaymentCode()),
            'TroposApostolhs'=> 'ΑΠΟΣΤΟΛΗ ΜΕ COURIER',
            'SkoposDiak'    => 'ΠΩΛΗΣΗ',
            'ToposFortoshs' => 'ΕΔΡΑΜΑΣ',
            'Comments'      => $order->getComment(),
            'Comments2'     => '',
            'Comments3'     => '',
            'CustName'      => $customer_name,
            'CustAfm'       => $order->isIsInvoiceOrder() ? $order->getVatNumber() : '',
            'ShippingCity'  => $order->getShippingCity(),
            'ShippingAddress'=> $order->getShippingAddress(),
            'ShippingZip'   => $order->getShippingPostcode(),
            'CustCity'      => $order->getPaymentCity(),
            'CustAddress'   => $order->getPaymentAddress(),
            'CustZip'       => $order->getPaymentPostcode(),
            'CustEmail'     => $order->getEmail(),
            'CustMobile'    => $order->getTelephone(),
            'CustTel'       => '',
            'CustTimok'     => '02',
            'CustFax'       => '',
            'CustDOY'       => $order->isIsInvoiceOrder() ? $order->getDoy() : '',
            'ShippingDate'  => '',
            'EmpUsername'   => $order->getEmail(),
            'InvoiceCode'   => $order->isIsInvoiceOrder() ? '01' : '05',
            'items'         => $prisma_products
        ];
    }

    private function prepareProducts(&$products): array
    {
        $return_data = array();
        if(empty($products))
        {
            return [];
        }

        foreach($products as $product)
        {
            $return_data[] = [
                'storecode' => $product->getModel(),
                'qty'       => $product->getQuantity(),
                'pricefpa'  => $product->getPrice(),
                'itemcomment' => $product->getName(),
                'discount'  => '',
                'tax'       => ''
            ];
        }

        return $return_data;
    }

    private function getPaymentMethodsForPrisma($find_payment_method)
    {

        $payments_prisma = [
            '01'  => '',
            '03'  => 'xpayment2',
            '09'  => 'pc_eurobank',
            '08'  => 'xpayment1'
        ];

        if(!isset($find_payment_method) && !isset($payments_prisma[$find_payment_method]))
        {
            return 0;
        }

        return array_keys($payments_prisma,$find_payment_method)[0] ?? '';
    }

    private function insertCustomer($customer)
    {
        $response = $this->client->request('POST', Prisma::$URL . '/' . Prisma::$INSERT_CUSTOMER, [
            'body' => [
                'SiteKey'    => Prisma::$SITE_KEY,
                'JsonStrWeb' => json_encode($customer, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ]
        ]);
    
        return json_decode(json_encode((array)simplexml_load_string($response->getContent())), true);
    }

    private function insertOrder($order)
    {

        $response = $this->client->request('POST', Prisma::$URL . '/' . Prisma::$INSERT_ORDER, [
            'body' => [
                'SiteKey'    => Prisma::$SITE_KEY,
                'JsonStrWeb' => json_encode($order, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ]
        ]);
    
        return json_decode(json_encode((array)simplexml_load_string($response->getContent())), true);
    }
}
