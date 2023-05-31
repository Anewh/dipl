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

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }


    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('fullName'),
            // ArrayField::new('fields'),
            AssociationField::new('fields')
                ->setFormTypeOption('choice_label', 'header')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('Teams')
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('users')
                ->setFormTypeOption('choice_label', 'lastname')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('storage')
                ->setFormTypeOption('choice_label', 'link')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('pages')
                ->setFormTypeOption('choice_label', 'header')
                ->setFormTypeOption('mapped', 'false')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
                // ->setFormTypeOption('asdsa', 'asdad')
                
                
            //TextEditorField::new('description'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        // this action executes the 'renderInvoice()' method of the current CRUD controller
        //$save = Action::new('save', 'Сохранить', 'fa fa-file-invoice')
        //     ->linkToCrudAction('save');

        return $actions
        //     ->update(Crud::PAGE_EDIT, Action::EDIT, function(Action $action) {
        //         return $action
        //             ->linkToCrudAction('save');
        //     })

            ->add(Crud::PAGE_EDIT, Action::new('save', 'Сохранить')
                ->setLabel('Label')
                ->linkToCrudAction('save')
                ->addCssClass('action-update btn btn-primary')
            )
        ;
    }

    public function save(AdminContext $context)
    {
        $fild = $context->getEntity()->getInstance();
        
        dd($fild);

        // add your logic here...
    }
    
    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);

        $this->addListener($formBuilder);

        return $formBuilder;
    }

    protected function addListener(FormBuilderInterface $formBuilder): void
    {
        $formBuilder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {

            //dd($event->getData());
            //dd($event->getData());

            // /** @var Moderator $moderator */
            // $moderator = $event->getData();

            // if (!$this->isThisUser($moderator)) {
            //     $event->getForm()->addError(new FormError(
            //         $this->translator->trans('moderators.error.change_other_password', [], 'admin')
            //     ));
            //     return;
            // }
            // if ($moderator->getPassword()) {
            //     $moderator->setPassword(
            //         $this->passwordHasher->hashPassword(
            //             $moderator,
            //             $moderator->getPassword()
            //         )
            //     );
            // }
        });
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setBlogPostSlug'],
        ];
    }

    public function setBlogPostSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        dd($entity);
    }
    
}
