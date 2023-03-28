<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Manufacturer;
use App\Entity\Categories;
use App\Entity\Products;
use App\Entity\Customers;
use App\Entity\Orders;

use Symfony\Component\HttpClient\CurlHttpClient as HttpClient;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use App\Repository\ManufacturerRepository;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;

class DashboardController extends AbstractDashboardController
{
    

    private ManufacturerRepository $manufacturerRepository;
    private CategoriesRepository $categoriesRepository;
    private ProductsRepository $productsRepository;

    public function __construct(
        ManufacturerRepository $manufacturerRepository,
        CategoriesRepository $categoriesRepository,
        ProductsRepository $productsRepository
    )
    {
        $this->manufacturerRepository = $manufacturerRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->productsRepository = $productsRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $allManufacturers = count($this->manufacturerRepository
            ->findAll());

        $allCategories = count($this->categoriesRepository
            ->findAll());

        $allProducts = count($this->productsRepository
            ->findAll());    

        // return parent::index();


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/index.html.twig',[
            'allManufacturers' => $allManufacturers,
            'allCategories'    => $allCategories,
            'allProducts'      => $allProducts,
            ]);
    }

    #[Route('/admin/datatable', name: 'admin_data_table')]
    public function dataTable(): Response
    {

        return $this->render('admin/data_tables/index.html.twig');
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MS Webout');
    }

    public function configureAssets(): Assets
    {
        
        return Assets::new()
        ->addCssFile('https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css')
        ->addJsFile('https://code.jquery.com/jquery-3.6.4.min.js')
        ->addJsFile('https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js')
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        // yield MenuItem::subMenu('Manufactuer', 'fas fa-book')->setSubItems([
        //     MenuItem::linkToCrud('Manufactuer', 'fas fa-industry', Manufacturer::class)
        // ]);
        yield MenuItem::section('Catalog');
        yield MenuItem::subMenu('Products', 'fa-solid fa-layer-group')->setSubItems([
            MenuItem::linkToCrud('Products', 'fa-solid fa-layer-group', Products::class),
            MenuItem::linkToUrl('Products Data Live', 'fa-solid fa-layer-group', '/admin/datatable')
        ]);

        yield MenuItem::linkToCrud('Categories', 'fa-solid fa-layer-group', Categories::class);
        yield MenuItem::linkToCrud('Manufacturer', 'fas fa-industry', Manufacturer::class);

        yield MenuItem::section('Customer');
        yield MenuItem::linkToCrud('Customers', 'fas fa-user', Customers::class);

        yield MenuItem::section('Sales');
        yield MenuItem::linkToCrud('Orders', 'fa fa-shopping-cart', Orders::class);
        
        yield MenuItem::section('Cron Jobs');
        yield MenuItem::linkToUrl('Cron Jobs', 'fa fa-calendar-check-o', '/admin?crudAction=index&crudControllerFqcn=App\Controller\Admin\CronJobCrudController');
        yield MenuItem::linkToUrl('Report', 'fa fa-calendar-check-o', '/admin?crudAction=index&crudControllerFqcn=App\Controller\Admin\CronReportCrudController&sort[id]=DESC');

        yield MenuItem::section('');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToDashboard('Settings', 'fa-solid fa-gears');
        
    }
}
