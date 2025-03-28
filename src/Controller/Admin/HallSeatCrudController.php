<?php

namespace App\Controller\Admin;

use App\Entity\HallSeat;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class HallSeatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HallSeat::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(EntityFilter::new('hall'));
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = parent::configureFields($pageName);

        $fields[] = AssociationField::new('hall')->autocomplete();

        return $fields;
    }
}
