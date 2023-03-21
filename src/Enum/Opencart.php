<?php

namespace App\Enum;

class Opencart {

    //Credentials
    public static string $TOKEN_API = 'xklTaedWJORzhqs7';

    public static string $URL = 'https://xikis.webdevs.gr/index.php?route=extension/feed/prisma_win';

    public static string $GET_MANUFACTURER  = 'prismaManufacturers';
    public static string $GET_CATEGORIES  = 'prismaCategories';
    public static string $GET_PRODUCTS  = 'prismaProducts';
    public static string $GET_PRODUCTS_DISCOUNT  = 'prismaProductSpecials';
    public static string $GET_PRODUCTS_OPTIONS  = 'prismaProductOptions';
    public static string $GET_OPTIONS  = 'prismaOptions';

    public static string $GET_ORDERS = 'prismaOrders';
    public static string $UPDATE_STATUS_ORDERS = 'prismaUpdateOrders';


    public function index()
    {

    }

}