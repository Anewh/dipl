<?php

namespace App\Controller\Admin;

use App\Entity\Storage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Routing\Annotation\Route;

//#[Route('/admin_', name: 'admin_project')]
class StorageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Storage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Репозиторий')
        ->setEntityLabelInPlural('Репозитории')
        ->setPageTitle('index', 'Репозитории')
        ->setPageTitle('new', 'Добавление репозитория')
        ->setPageTitle('edit', 'Изменить репозиторий')
        ;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::EDIT, function(Action $action) {
                return $action
                    ->setLabel('Изменить');
            })
            ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
                return $action
                    ->setLabel('Добавить');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function(Action $action) {
                return $action
                    ->setLabel('Удалить');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function(Action $action) {
                return $action
                    ->setLabel('Сохранить и продолжить редактирование');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function(Action $action) {
                return $action
                    ->setLabel('Сохранить и вернуться на главную');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function(Action $action) {
                return $action
                    ->setLabel('Сохранить и добавить еще');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function(Action $action) {
                return $action
                    ->setLabel('Сохранить и вернуться на главную');
            })
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),

            TextField::new('link')->setLabel('Название'),
            TextField::new('author')->setLabel('Автор(Github nick)'),
            TextEditorField::new('description')->setLabel('Описание'),
            AssociationField::new('project')
                ->setLabel('Проект')
                ->setFormTypeOption('choice_label', 'fullName')
                ->setFormTypeOption('mapped', 'false')
        ];
    }

}