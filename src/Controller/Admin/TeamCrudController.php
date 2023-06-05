<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Team::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Команда')
        ->setEntityLabelInPlural('Команды')
        ->setPageTitle('index', 'Команды')
        ->setPageTitle('new', 'Добавление команды')
        ->setPageTitle('edit', 'Изменить команду')
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
            //IdField::new('id'),
            TextField::new('name')
                ->setLabel('Название')
                ->setFormTypeOption('label', 'Название команды'),
            AssociationField::new('users')
                ->setLabel('Участники')
                ->setFormTypeOption('label', 'Участники')
                ->setFormTypeOption('choice_label', 'lastname')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('projects')
                ->setLabel('Проекты')
                ->setFormTypeOption('label', 'Проекты')
                ->setFormTypeOption('choice_label', 'fullName')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
    }
    
}
