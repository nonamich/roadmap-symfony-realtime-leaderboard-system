<?php

namespace App\Controller\Admin;

use App\Entity\Showtime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
        $fields = parent::configureFields($pageName);

        $fields[] = AssociationField::new('movie')->autocomplete();
        $fields[] = AssociationField::new('hall')->autocomplete();

        return $fields;
    }
}
