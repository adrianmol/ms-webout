<?php

namespace App\Controller\Prisma;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\OrdersRepository;
use App\Enum\Prisma;
use App\Enum\Opencart;

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

    #[Route('/prisma/orders', name: 'app_prisma_send_orders')]
    public function index(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $orders = new OrdersRepository($this->doctrine);

        $orders_data = $orders->findAllOrdersThatDoentSentToErp();

        if (empty($orders_data)) {
            return $this->json([]);
        }

        foreach ($orders_data as $order) {

            $prepare_customer_data = $this->prepareCustomer($order);
            $prisma_customer = $this->insertCustomer($prepare_customer_data);

            $order->products = $this->doctrine->getRepository(\App\Entity\OrderProducts::class)->findBy(['order_id' => $order->getEshopOrderId()]);
            $order->totals   = $this->doctrine->getRepository(\App\Entity\OrderTotals::class)->findBy(['order_id' => $order->getEshopOrderId()]);

            $prepare_order_data = $this->prepareOrder($order, $order->products);
            $prisma_order = $this->insertOrder($prepare_order_data);

            if (isset($prisma_order[0])) {

                if ((int)$prisma_order[0] > 0) {
                    $order->setErpOrderId($prisma_order[0])
                          ->setErpStatusId(0);
                    $entityManager->flush();
                }

                $data_eshop['orders'][] = [
                    'prisma_id' => $prisma_order[0],
                    'order_id'  => $order->getEshopOrderId(),
                    'status'    => 0
                ];
            }
        }

        if (isset($data_eshop)) {
            $this->updateOrderEshop($data_eshop);
        }

        return $this->json($data_eshop);
        // return $this->render('prisma/orders/index.html.twig', [
        //     'controller_name' => 'OrdersController',
        // ]);
    }

    #[Route('/prisma/orders/get-status', name: 'app_prisma_get_orders_status')]
    public function getOrdersStatus(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $orders = new OrdersRepository($this->doctrine);
        $data_eshop['orders'] = array ();

        $_orders = $orders->findAllOrdersThatHaveToChangeStatus();

        if(empty($_orders)){
            return $this->json(['message' => 'No orders were found']);
        }

        $orders_status_prisma = $this->getOrdersStatusPrisma($_orders);

        if(empty($orders_status_prisma)){
            return $this->json(['message' => 'No orders were found in Prisma']);
        }

        foreach($orders_status_prisma->OrderStatus as $prisma_order){
            
            $prisma_order = json_decode(json_encode($prisma_order), true);
            
            if(empty($prisma_order['OrderNo']))
            {
                continue;
            }

            $repo_order = $orders->findByErpOrdersId($prisma_order['OrderNo']);

            if(!empty($repo_order) && $repo_order->getErpStatusID() != $prisma_order['Status'])
            {

                $repo_order->setErpStatusId($prisma_order['Status'])
                ->setVoucherNumber($prisma_order['VoucherNo'])
                ->setErpShippingMethod($prisma_order['ShippingMethod']);

                $data_eshop['orders'][] = [
                    'prisma_id' => $repo_order->getErpOrderId(),
                    'order_id'  => $repo_order->getEshopOrderId(),
                    'status'    => $prisma_order['Status'],
                    'voucher_number'      => $repo_order->getVoucherNumber(),
                    'erp_shipping_method' => $repo_order->getErpShippingMethod(),
                ];

                $entityManager->flush();
            }
        }

        if (!empty($data_eshop['orders'])) {
            $this->updateOrderEshop($data_eshop);
        }

        //dd($_orders,$orders_status_prisma,$data_eshop);
        return $this->json($data_eshop);
    }

    private function prepareCustomer($order)
    {

        $customer_name = $order->isIsInvoiceOrder() ? $order->getPaymentCompany() : $order->getLastName() . ' ' . $order->getFirstName();

        return [
            'CustUsername'    => $order->getEmail(),
            'CustName'        => $customer_name,
            'CustAfm'         => $order->isIsInvoiceOrder() ? $order->getVatNumber() : '',
            'CustFpa'         => '01',
            'ShippingCity'    => $order->getPaymentCity(),
            'CustCity'        => $order->getPaymentCity(),
            'CustAddress'     => $order->getPaymentAddress(),
            'ShippingAddress' => $order->getPaymentAddress(),
            'CustZip'         => $order->getPaymentPostCode(),
            'ShippingZip'     => $order->getPaymentPostCode(),
            'CustEmail'       => $order->getEmail(),
            'CustMobile'      => $order->getTelephone(),
            'CustTel'         => '',
            'CustDOY'         => $order->isIsInvoiceOrder() ? $order->getDoy() : '',
            'CustPricelist'   => '02',
            'CustFax'         => '',
            'CustBusiness'    => $order->isIsInvoiceOrder() ? $order->getProfession() : 'ΙΔΙΩΤΗΣ',
            'CustKepyo'       => $order->isIsInvoiceOrder() ? '01' : '05',
            'CustCountryCode' => 'EL',
            'CustPaymentMethodCode' => $this->getPaymentMethodsForPrisma($order->getPaymentCode()),
        ];
    }

    private function prepareOrder(&$order, &$products)
    {

        $customer_name = $order->isIsInvoiceOrder() ? $order->getPaymentCompany() : $order->getLastName() . ' ' .$order->getFirstName() ;
        $prisma_totals = $this->prepareTotalsAsProduct($order->totals);
        $prisma_products = $this->prepareProducts($products);

        $prisma_products = array_merge($prisma_products, $prisma_totals);

        return [
            'CustUsername'  => $order->getEmail(),
            'OrderNo'       => $order->getEshopOrderId(),
            'StorageCode'   => '000',
            'SeiraPar'      => 'A',
            'TroposPliromis' => $this->getPaymentMethodsForPrisma($order->getPaymentCode()),
            'TroposApostolhs' => $this->getShippingMethodsForPrisma($order->getShippingCode()),
            'SkoposDiak'    => 'ΠΩΛΗΣΗ',
            'ToposFortoshs' => 'ΕΔΡΑΜΑΣ',
            'Comments'      => $order->getComment(),
            'Comments2'     => '',
            'Comments3'     => '',
            'CustName'      => $customer_name,
            'CustAfm'       => $order->isIsInvoiceOrder() ? $order->getVatNumber() : '',
            'ShippingCity'  => $order->getShippingCity(),
            'ShippingAddress' => $order->getShippingAddress(),
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

    private function prepareTotalsAsProduct($totals): array
    {
        $prisma_totals = [];
        $shipping = ['shipping', 'xshippingpro.1'];
        $fee      = ['xfeepro', 'cod'];

        if (empty($totals)) {
            return [];
        }
        foreach ($totals as $total) {
            if (in_array($total->getCode(), $shipping) && $total->getValue() > 0) {
                $prisma_totals[] = [
                    'storecode' => '01',
                    'qty'       => 1,
                    'pricefpa'  => $total->getValue(),
                    'itemcomment' => $total->getTitle(),
                    'discount'  => '',
                    'tax'       => ''
                ];
            }

            if (in_array($total->getCode(), $fee) && $total->getValue() > 0) {
                $prisma_totals[] = [
                    'storecode' => '03',
                    'qty'       => 1,
                    'pricefpa'  => $total->getValue(),
                    'itemcomment' => $total->getTitle(),
                    'discount'  => '',
                    'tax'       => ''
                ];
            }
        }

        return $prisma_totals;
    }

    private function prepareProducts(&$products): array
    {
        $return_data = array();
        if (empty($products)) {
            return [];
        }

        foreach ($products as $product) {
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
            '01'  => 'xshippingpro.xshippingpro4',
            '03'  => 'xpayment2',
            '09'  => 'pc_eurobank',
            '08'  => 'xpayment1'
        ];

        if (!isset($find_payment_method) && !isset($payments_prisma[$find_payment_method])) {
            return 0;
        }

        return array_keys($payments_prisma, $find_payment_method)[0] ?? '';
    }

    private function getShippingMethodsForPrisma($find_shipping_method)
    {

        $shippings_prisma = [
            'ΑΠΟΣΤΟΛΗ ΜΕ COURIER'  => 'xshippingpro.xshippingpro1',
            'ΑΠΟΣΤΟΛΗ ΜΕ COURIER'  => 'xshippingpro.xshippingpro3',
            'ΑΠΟΣΤΟΛΗ ΜΕ COURIER'  => 'xshippingpro.xshippingpro2',
            'ΠΑΡΑΛΑΒΗ ΑΠΟ ΤΟ ΚΑΤΑΣΤΗΜΑ'  => 'xshippingpro.xshippingpro4',
        ];

        if (!isset($find_shipping_method) && !isset($shippings_prisma[$find_shipping_method])) {
            return 0;
        }

        return array_keys($shippings_prisma, $find_shipping_method)[0] ?? '';
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

    private function updateOrderEshop($orders)
    {
        $response = $this->client->request('POST', Opencart::$URL . '/' . Opencart::$UPDATE_STATUS_ORDERS, [
            'headers' => [
                'X-OC-RESTADMIN-ID' => Opencart::$TOKEN_API,
                'Content-Type'      => 'application/json'
            ],
            'body'    => json_encode($orders)
        ]);

        return json_decode(($response->getContent()), true);
    }

    private function getOrdersStatusPrisma($orders)
    {

        if (empty($orders)) {
            return [];
        }

        foreach ($orders as $order) {
            if (!empty($order['erp_order_id'])) {
                $data['Orders'][] = ['OrderNo' => $order['erp_order_id'], 'Seira' => 'A'];
            }
        }

        $response = $this->client->request('POST', Prisma::$URL . '/' . Prisma::$GET_ORDER_STATUS, [
            'body' => [
                'SiteKey'    => Prisma::$SITE_KEY,
                'Date'       => '01-01-2022',
                'JsonStrWeb' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ]
        ]);

        return simplexml_load_string($response->getContent());
    }
}
