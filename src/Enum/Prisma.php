<?php

namespace App\Enum;

class Prisma {

    //Credentials
    public static string $SITE_KEY = 'kk-gg161-348';
    public static array $STORAGE_CODE = ['000'];

    public static string $URL = 'https://ecommercews.megasoft.gr/eCommerceWebService.asmx';

    public static string $GET_MANUFACTURER  = 'GetManufacturers';
    public static string $GET_CATEGORIES  = 'GetItemGroups';
    public static string $GET_PRODUCTS = 'GetProducts';
    public static string $GET_DISABLED_PRODUCTS = 'GetItemsWithNoEshop';
    public static string $GET_CUSTOM_FIELDS = 'GetCustomFields';

    public static string $INSERT_CUSTOMER = 'InsertCustomer';

    public static string $INSERT_ORDER = 'InsertOrder';
    public static string $GET_ORDER_STATUS = 'GetOrderStatus';

    public static string $UPLOAD_IMAGE_TO_FTP = 'UploadImageToFtp';

    public function index()
    {

    }
}