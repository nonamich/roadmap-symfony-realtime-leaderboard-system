<?php

namespace App\Controller\Admin;

use App\Entity\ReservedSeat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReservedSeatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReservedSeat::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id');
        yield AssociationField::new('reservation')->autocomplete();
        yield AssociationField::new('showtimeSeat')->autocomplete();
    }
}
