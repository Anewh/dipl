<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Team::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('users')
                ->setFormTypeOption('choice_label', 'lastname')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('projects')
                ->setFormTypeOption('choice_label', 'fullName')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
    }
    
}
