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

            $customer = $this->prepareCustomer($order);
            $prisma_customer = $this->insertCustomer($customer);
            dd($customer);
            dd($prisma_customer);
 
            $order->products = $this->doctrine->getRepository(\App\Entity\OrderProducts::class)->findAll(['order_id' => $order->getEshopOrderId()]);
            $order->totals   = $this->doctrine->getRepository(\App\Entity\OrderTotals::class)->findAll(['order_id' => $order->getEshopOrderId()]);



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

    private function insertCustomer($customer)
    {
        $response = $this->client->request('POST', Prisma::$URL . '/' . Prisma::$INSERT_CUSTOMER, [
            'body' => [
                'SiteKey'    => Prisma::$SITE_KEY,
                'JsonStrWeb' => json_encode($customer)
            ]
        ]);
    
        return json_decode(json_encode((array)simplexml_load_string($response->getContent())), true);
    }
}
