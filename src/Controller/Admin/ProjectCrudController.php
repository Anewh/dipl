<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin_project', name: 'admin_project')]
class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Проект')
            ->setEntityLabelInPlural('Проекты')
            ->setPageTitle('index', 'Проекты')
            ->setPageTitle('new', 'Добавление проекта')
            ->setPageTitle('edit', 'Изменить проект');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('fullName')
                ->setLabel('Полное название')
                ->setFormTypeOption('label', 'Полное название'),
            TextField::new('codeName')
                ->setLabel('Условное название')
                ->setFormTypeOption('label', 'Условное название'),
            TextField::new('type')
                ->setLabel('Тип')
                ->setFormTypeOption('label', 'Тип'),
            AssociationField::new('teams')
                ->setLabel('Команды')
                ->setFormTypeOption('label', 'Команды в составе проекта')
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('users')
                ->setLabel('Пользователи')
                ->setFormTypeOption('label', 'Пользователи проекта')
                ->setFormTypeOption('choice_label', 'lastname')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('storages')
                ->setLabel('Репозитории')
                ->setCustomOption('label', 'Репозиторий')
                ->setFormTypeOption('label', 'Репозитории')
                ->setFormTypeOption('choice_label', 'link')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action
                    ->setLabel('Изменить');
            })
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setLabel('Добавить');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action
                    ->setLabel('Удалить');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action
                    ->setLabel('Сохранить и продолжить редактирование');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action
                    ->setLabel('Сохранить и вернуться на главную');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function (Action $action) {
                return $action
                    ->setLabel('Сохранить и добавить еще');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action
                    ->setLabel('Сохранить и вернуться на главную');
            });
    }
}
