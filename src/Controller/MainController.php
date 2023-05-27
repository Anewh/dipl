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
        // $client = Client::createWithHttpClient(new HttplugClient());
        // $client->authenticate('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', null, \Github\AuthMethod::ACCESS_TOKEN);
        
        // $commits = $client->api('repo')->commits()->all('Terqaz', 'diplom_app', array('sha' => 'master'));
        // $branches = $client->api('repo')->branches('GrishaginEvgeny', 'IntaroPracticeProject');
        // dd($branches);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
