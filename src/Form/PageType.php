<?php

namespace App\Form;

use App\Entity\Page;
use App\Entity\Project;
use App\Repository\PageRepository;
use App\Repository\ProjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{

    private ProjectRepository $projectRepository;
    private PageRepository $pageRepository;

    public function __construct(ProjectRepository $projectRepository, PageRepository $pageRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->pageRepository = $pageRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('header', TextType::class, [
                'label' => 'Заголовок'
                ])
            ->add('file', TextType::class, [
                'label' => 'Содержимое страницы'
                ])
            ->add('parent', EntityType::class, [
                'label' => 'Родитель',
                'class' => Page::class,
                'choices' => $this->pageRepository->findByProject($this->projectRepository->findById($options['project_id'])),
                'choice_label' => 'header'
                ])
            ->add('project', EntityType::class, [
                'label' => 'Проект',
                'class' => Project::class,
                'choices' => $this->projectRepository->findById($options['project_id']),
                'choice_label' => 'fullName'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
        $resolver->setRequired(['project_id']);
        $resolver->setAllowedTypes('project_id', 'int');
    }
}
