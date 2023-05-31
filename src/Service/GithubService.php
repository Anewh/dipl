<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\Storage;
use App\Exception\BillingUnavailableException;
use Doctrine\ORM\EntityManagerInterface;
use Github\Client;
use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class GithubService
{
    private const DATE_FORMAT = 'Y-m-d';
    private ChartBuilderInterface $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder, EntityManagerInterface $em)
    {
        $this->chartBuilder = $chartBuilder;

    }

    // TODO обработка ошибки доступа пользователя к проекту - если проект приватный и у текущего пользователя нет прав на чтение
    // TODO обработка ошибки доступа пользователя к проекту - если пользователь - заказчик
    // TODO убрать дублирование кода и сделать хоть что-то адекватно читабельным
    // TODO поправить кривые названия в сущностях

    // Получить данные о всех репозиториях проекта по всем веткам, отдельно по репозиторию, отдельно по каждой ветке 
    public function getAccumulatedData($project, $user){
        $chartProject = $this->getProjectDataset($project, $user);
        $storages = $project->getStorage();

        $chartStorages = [];
        $chartBranches = [];

        $client = Client::createWithHttpClient(new HttplugClient());
        $client->authenticate($user->getToken(), null, \Github\AuthMethod::ACCESS_TOKEN);


        foreach($storages as $storage){
            array_push($chartStorages, $this->getStorageDataset($storage, $user));
            $branches = $client->api('repo')->branches($storage->getAuthor(), $storage->getLink());
            foreach($branches as $branch){
                array_push($chartBranches, $this->getBrancheDataset($storage, $branch['name'], $user));
            }
        }

        return [
            'chartProject' => $chartProject,
            'chartStorages' => $chartStorages,
            'chartBranches' => $chartBranches
        ];
    }


    // Получить данные о всех репозиториях проекта по всем веткам
    public function getProjectDataset($project, $user)
    {
        $client = Client::createWithHttpClient(new HttplugClient());
        $client->authenticate($user->getToken(), null, \Github\AuthMethod::ACCESS_TOKEN);

        $storages = $project->getStorage()->toArray();

        // ветки по всем репозиториям 
        $branches = [];

        //dd($storages);
        foreach ($storages as $s) {
            array_push($branches, ([
                'data' => $client->api('repo')->branches($s->getAuthor(), $s->getLink()),
                'storage' => $s
            ]));
        }


        $commits = [];
        $dates = [];
        $authors = [];


        foreach ($branches as $repos) {

            foreach ($repos['data'] as $branch) {
                $e = $client->api('repo')->commits()->all($repos['storage']->getAuthor(), $repos['storage']->getLink(), array('sha' => $branch['name']));
                array_push($commits, $e);

                foreach ($e as $j) {
                    array_push($dates, $j['commit']['author']['date']);
                    array_push($authors, $j['commit']['author']['name']);
                }
            }
        }


        // активность по всем веткам всех репозиториев проекта
        $chart = $this->chartBuilder->createChart(Chart::TYPE_RADAR);
        $data = (array_count_values($authors));


        $labels = [];
        $dataset = [];
        foreach ($data as $key => $d) {
            array_push($labels, $key);
            array_push($dataset, $d);
        }



        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'symfony',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $dataset,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => max($dataset),
                ],
            ],
        ]);

        return $chart;
    }

    // Получить активность по всем веткам конкретного репозитория
    public function getStorageDataset($storage, $user)
    {
        $client = Client::createWithHttpClient(new HttplugClient());
        $client->authenticate($user->getToken(), null, \Github\AuthMethod::ACCESS_TOKEN);

        // ветки по репозиторию
        $branches = $client->api('repo')->branches($storage->getAuthor(), $storage->getLink());

        $commits = [];
        $dates = [];
        $authors = [];

        foreach ($branches as $branch) {
            $e = $client->api('repo')->commits()->all($storage->getAuthor(), $storage->getLink(), array('sha' => $branch['name']));
            array_push($commits, $e);

            foreach ($e as $j) {
                array_push($dates, $j['commit']['author']['date']);
                array_push($authors, $j['commit']['author']['name']);
            }
        }

        // активность по всем веткам всех репозиториев проекта
        $chart = $this->chartBuilder->createChart(Chart::TYPE_RADAR);
        $data = (array_count_values($authors));

        $labels = [];
        $dataset = [];
        foreach ($data as $key => $d) {
            array_push($labels, $key);
            array_push($dataset, $d);
        }

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'symfony',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $dataset,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => max($dataset),
                ],
            ],
        ]);

        return $chart;
    }

    // Получить активность а конкретной ветке указанного репозитория
    public function getBrancheDataset($storage, $sha, $user)
    {
        $client = Client::createWithHttpClient(new HttplugClient());
        $client->authenticate($user->getToken(), null, \Github\AuthMethod::ACCESS_TOKEN);

        // ветки по репозиторию
        $dates = [];
        $authors = [];

        $commits = $client->api('repo')->commits()->all($storage->getAuthor(), $storage->getLink(), array('sha' => $sha));

        foreach ($commits as $commit) {
            array_push($dates, $commit['commit']['author']['date']);
            array_push($authors, $commit['commit']['author']['name']);
        }

        // активность по всем веткам всех репозиториев проекта
        $chart = $this->chartBuilder->createChart(Chart::TYPE_RADAR);
        $data = (array_count_values($authors));

        $labels = [];
        $dataset = [];
        foreach ($data as $key => $d) {
            array_push($labels, $key);
            array_push($dataset, $d);
        }

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'symfony',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $dataset,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => max($dataset),
                ],
            ],
        ]);

        return $chart;
    }

    public function getRenderData()
    {

    }

}