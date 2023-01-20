<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Manufacturer;
use App\Entity\Categories;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ManufacturerRepository;
use App\Repository\CategoriesRepository;

class DashboardController extends AbstractDashboardController
{
    

    private ManufacturerRepository $manufacturerRepository;
    private CategoriesRepository $categoriesRepository;

    public function __construct(
        ManufacturerRepository $manufacturerRepository,
        CategoriesRepository $categoriesRepository
        )
    {
        $this->manufacturerRepository = $manufacturerRepository;
        $this->categoriesRepository = $categoriesRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $allManufacturers = count($this->manufacturerRepository
            ->findAll());

        $allCategories = count($this->categoriesRepository
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
            'allCategories'    => $allCategories]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MS Webout');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        
        // yield MenuItem::subMenu('Manufactuer', 'fas fa-book')->setSubItems([
        //     MenuItem::linkToCrud('Manufactuer', 'fas fa-industry', Manufacturer::class)
        // ]);
        yield MenuItem::section('Catalog');
        yield MenuItem::linkToCrud('Manufacturer', 'fas fa-industry', Manufacturer::class);
        yield MenuItem::linkToCrud('Categories', 'fa-solid fa-layer-group', Categories::class);

        yield MenuItem::section('');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToDashboard('Settings', 'fa-solid fa-gears');
        
    }
}
