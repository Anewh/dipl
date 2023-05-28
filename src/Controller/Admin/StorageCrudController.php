<?php

namespace App\Controller\Admin;

use App\Entity\Storage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StorageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Storage::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),

            TextField::new('link'),
            TextEditorField::new('description'),
            AssociationField::new('project')
                ->setFormTypeOption('choice_label', 'fullName')
                ->setFormTypeOption('mapped', 'false')
        ];
    }

}