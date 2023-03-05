<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

class CategoriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categories::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $getCategories = Action::new('getCategories')
        ->linkToRoute('app_prisma_categories')
        ->addCssClass('btn btn-primary')
        ->createAsGlobalAction();
        
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('category_id')
            ->add(BooleanFilter::new('status'))
            ->add(BooleanFilter::new('eshop_status'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('category_id'),
            IdField::new('parent_id'),
            TextField::new('categoryName'),
            TextField::new('category_code'),
            BooleanField::new('status'),
            BooleanField::new('eshop_status'),
        ];
    }

}
