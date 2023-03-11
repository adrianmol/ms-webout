<?php

namespace App\Controller\Admin;

use Cron\CronBundle\Entity\CronReport;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class CronReportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CronReport::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('run_at'),
            NumberField::new('run_time'),
            NumberField::new('exit_code'),
            TextEditorField::new('output'),
            TextareaField::new('error'),
        ];
    }

}
