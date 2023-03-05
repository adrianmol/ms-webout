<?php

namespace App\Controller\Admin;

use App\Entity\Customers;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CustomersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Customers::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
