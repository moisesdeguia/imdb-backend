<?php

namespace App\Controller\Admin;

use App\Entity\Actor;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ActorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Actor::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnDetail()->hideOnIndex(),
            TextField::new('name'),
            Datefield::new('date_born'),
            Datefield::new('date_dead'),
            TextField::new('place_born'),
        ];
    }
}
