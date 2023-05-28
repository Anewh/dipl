<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class PageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            // TextField::new('title'),
            TextEditorField::new('file'),
            AssociationField::new('project')
            ->setFormTypeOption('choice_label', 'fullName')
            ->setFormTypeOption('mapped', 'false')
            ->setFormTypeOptions([
                'by_reference' => false,
            ]),
        ];
    }
    
}
