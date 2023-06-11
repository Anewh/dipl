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
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ProjectCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Вернуться на сайт', 'fas fa-home', 'app_project_index');
        yield MenuItem::linkToCrud('Проекты', 'fa fa-home', Project::class);
        yield MenuItem::linkToCrud('Репозитории', 'fa fa-home', Storage::class);
        yield MenuItem::linkToCrud('Пользователи', 'fa fa-home', User::class);
        yield MenuItem::linkToCrud('Команды', 'fa fa-home', Team::class);
    }
}
