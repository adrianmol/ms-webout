<?php

namespace App\Controller\Admin;

use Cron\CronBundle\Entity\CronReport;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CronReportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CronReport::class;
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
