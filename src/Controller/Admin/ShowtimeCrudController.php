<?php

namespace App\Controller\Admin;

use App\Entity\Showtime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ShowtimeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Showtime::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->setDisabled()->setRequired(false);
        yield DateField::new('startTime');
        yield DateField::new('endTime');
        yield DateField::new('createdOn')->setDisabled()->setRequired(false);
        yield AssociationField::new('movie')->autocomplete();
        yield AssociationField::new('hall')->autocomplete();

    }
}
