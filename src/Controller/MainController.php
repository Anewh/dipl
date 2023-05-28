<?php

namespace App\Controller;

use Github\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {        
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
