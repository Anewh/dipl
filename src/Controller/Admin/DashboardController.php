<?php

namespace App\Controller\Admin;

use App\Entity\Field;
use App\Entity\Page;
use App\Entity\Project;
use App\Entity\Storage;
use App\Entity\Team;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ProjectCrudController::class)->generateUrl();

        return $this->redirect($url);
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Вернуться на сайт', 'fas fa-home', 'app_project_index');
        //yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('Поля', 'fa fa-home', Field::class);
        yield MenuItem::linkToCrud('Проекты', 'fa fa-home', Project::class);
        //yield MenuItem::linkToCrud('Страницы', 'fa fa-home', Page::class);
        yield MenuItem::linkToCrud('Репозитории', 'fa fa-home', Storage::class);
        yield MenuItem::linkToCrud('Пользователи', 'fa fa-home', User::class);
        yield MenuItem::linkToCrud('Команды', 'fa fa-home', Team::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
