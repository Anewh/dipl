<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\Storage;
use App\Entity\User;
use App\Exception\BillingUnavailableException;
use App\Repository\StorageRepository;
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
    private const COLORS = [
        '(0,255,127)',
        '(46,139,87)',
        '(255,165,0)',
        '(255,215,0)',
        '(184,134,11)',
        '(218,165,32)',
        '(238,232,170)',
        '(189,183,107)',
        '(240,230,140)',
        '(128,128,0)',
        '(255,255,0)',
        '(154,205,50)',
        '(85,107,47)',
        '(107,142,35)',
        '(124,252,0)',
        '(127,255,0)',
        '(102,205,170)',
        '(60,179,113)',
        '(32,178,170)',
        '(47,79,79)',
        '(0,128,128)',
        '(0,139,139)'
    ];

    private ChartBuilderInterface $chartBuilder;

    private Client $client;

    private EntityManagerInterface $em;

    public function __construct(string $githubAuthToken, ChartBuilderInterface $chartBuilder, EntityManagerInterface $em)
    {
        $this->chartBuilder = $chartBuilder;
        $this->em = $em;

        $this->client = Client::createWithHttpClient(new HttplugClient());
        $this->client->authenticate($githubAuthToken, null, \Github\AuthMethod::ACCESS_TOKEN);
    }

    /**
     * Получить графики количества коммитов по всем репозиториям проекта по всем веткам, 
     * отдельно по каждому репозиторию и отдельно по каждой ветке каждого репозитория
     */
    public function getAccumulatedData(Project $project)
    {
        /** @var StorageRepository $storageRepository */
        $storageRepository = $this->em->getRepository(Storage::class);

        $storages = $storageRepository->findByProjectIndexed($project->getId());

        // ветки по репозиториям
        $branches = [];
        foreach ($storages as $s) {
            $branches[$s->getId()] = $this->client->api('repo')->branches(
                $s->getAuthor(),
                $s->getLink()
            );
        }

        // Коммиты по веткам по репозиториям (storageId => branchName => [...])
        $commits = [];

        // Загружаем данные
        foreach ($branches as $storageId => $storageBranches) {
            $storage = $storages[$storageId];

            foreach ($storageBranches as $storageBranch) {
                $branchName = $storageBranch['name'];

                $branchCommits = $this->client->api('repo')->commits()->all(
                    $storage->getAuthor(),
                    $storage->getLink(),
                    ['sha' => $branchName]
                );

                foreach ($branchCommits as $commit) {
                    $commits[$storageId][$branchName][] = [
                        'date' => $commit['commit']['author']['date'],
                        'authorName' => $commit['commit']['author']['name'],
                    ];
                }
            }
        }

        // dd($commits);

        // Создаем графики
        $chartStorages = [];
        $chartBranches = [];
        foreach ($commits as $storageId => $storageCommits) {
            $chartStorages[] = $this->getStorageDataset($storage, $storageCommits);

            foreach ($storageCommits as $branchName => $branchCommits) {
                $chartBranches[] = $this->getBranchDataset($branchName, $branchCommits);
            }
        }

        return [
            'chartProject' => $this->getProjectDataset($project, $commits),
            'chartStorages' => $chartStorages,
            'chartBranches' => $chartBranches
        ];
    }

    /**
     * Получить данные о всех репозиториях проекта по всем веткам
     */
    private function getProjectDataset(Project $project, array $commits): Chart
    {
        $authors = [];
        foreach ($commits as $storageId => $storageCommits) {
            foreach ($storageCommits as $branchName => $branchCommits) {
                foreach ($branchCommits as $commit) {
                    $authors[] = $commit['authorName'];
                }
            }
        }

        $labels = [];
        $dataset = [];
        $commitCounts = array_count_values($authors);
        foreach ($commitCounts as $author => $count) {
            $labels[] = $author;
            $dataset[] = $count;
        }

        return $this->createRadarChart(
            $project->getFullname(),
            $labels,
            $dataset,
            backgroundColors: 'rgba(159, 170, 174, 0.7)',
            borderColor: 'rgb(95, 158, 160)'
        );
    }

    /**
     * Получить активность по всем веткам конкретного репозитория
     */
    private function getStorageDataset(Storage $storage, array $storageCommits): Chart
    {
        $authors = [];
        foreach ($storageCommits as $branchName => $branchCommits) {
            foreach ($branchCommits as $commit) {
                $authors[] = $commit['authorName'];
            }
        }

        // Формируем график
        $labels = [];
        $dataset = [];
        $commitCounts = array_count_values($authors);
        foreach ($commitCounts as $author => $count) {
            $labels[] = $author;
            $dataset[] = $count;
        }

        return $this->createRadarChart(
            $storage->getLink(),
            $labels,
            $dataset,
            backgroundColors: 'rgba(75, 0, 130, 0.5)',
            borderColor: 'rgba((147, 112, 219, 0.6)'
        );
    }

    /**
     * Получить активность по конкретной ветке указанного репозитория
     */
    private function getBranchDataset(string $branchName, array $branchCommits): Chart
    {
        $authors = [];
        foreach ($branchCommits as $commit) {
            $authors[] = $commit['authorName'];
        }

        // Формируем график
        $i = 0;
        $labels = [];
        $dataset = [];
        $backgroundColors = [];
        $commitCounts = array_count_values($authors);
        foreach ($commitCounts as $author => $count) {
            $labels[] =  $author;
            $dataset[] = $count;
            $backgroundColors[] = 'rgb' . self::COLORS[$i++];
        }

        return $this->createPieChart(
            $branchName,
            $labels,
            $dataset,
            backgroundColors: $backgroundColors,
            borderColor: 'rgb(255, 99, 132)'
        );
    }

    private function createRadarChart(string $name, array $labels, array $dataset, $backgroundColors, $borderColor): Chart
    {
        return $this->chartBuilder->createChart(Chart::TYPE_RADAR)
            ->setData([
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => $name,
                        'backgroundColor' => $backgroundColors,
                        'borderColor' => $borderColor,
                        'data' => $dataset,
                    ],
                ],
            ])
            ->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 0,
                        'suggestedMax' => !empty($dataset) ? max($dataset) : 0
                    ],
                ],
            ]);
    }

    private function createPieChart(string $name, array $labels, array $dataset, $backgroundColors, $borderColor): Chart
    {
        return $this->chartBuilder->createChart(Chart::TYPE_PIE)
            ->setData([
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'symfony',
                        'backgroundColor' => $backgroundColors,
                        'borderColor' => $borderColor,
                        'data' => $dataset,
                    ],
                ],
            ])
            ->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 0,
                        'suggestedMax' => !empty($dataset) ? max($dataset) : 0,
                    ],
                ],
                'plugins' => [
                    'legend' => [
                        'position' => 'top'
                    ],
                    'title' => [
                        'display' => 'true',
                        'text' => $name
                    ]
                ]
            ]);
    }

    /**
     * Получить данные обо всех репозиториях проекта по всем веткам, отдельно по репозиторию, отдельно по каждой ветке
     * @deprecated
     */
    public function getAccumulatedData2(Project $project)
    {
        $chartProject = $this->getProjectDataset2($project);
        $storages = $project->getStorages();

        $chartStorages = [];
        $chartBranches = [];

        foreach ($storages as $storage) {
            $chartStorages[] = $this->getStorageDataset2($storage);

            $branches = $this->client->api('repo')->branches(
                $storage->getAuthor(),
                $storage->getLink()
            );

            foreach ($branches as $branch) {
                $chartBranches[] = $this->getBranchDataset2($storage, $branch['name']);
            }
        }

        return [
            'chartProject' => $chartProject,
            'chartStorages' => $chartStorages,
            'chartBranches' => $chartBranches
        ];
    }

    /**
     * Получить данные о всех репозиториях проекта по всем веткам
     * @deprecated
     */
    public function getProjectDataset2(Project $project)
    {
        // ветки по всем репозиториям 
        $branches = [];
        foreach ($project->getStorages() as $s) {
            $branches[] = [
                'data' => $this->client->api('repo')->branches(
                    $s->getAuthor(),
                    $s->getLink()
                ),
                'storage' => $s
            ];
        }

        $dates = [];
        $authors = [];

        foreach ($branches as $repos) {

            foreach ($repos['data'] as $branch) {
                $e = $this->client->api('repo')->commits()->all(
                    $repos['storage']->getAuthor(),
                    $repos['storage']->getLink(),
                    ['sha' => $branch['name']]
                );

                foreach ($e as $j) {
                    $dates[] = $j['commit']['author']['date'];
                    $authors[] = $j['commit']['author']['name'];
                }
            }
        }


        // активность по всем веткам всех репозиториев проекта
        $data = array_count_values($authors);

        $labels = [];
        $dataset = [];
        foreach ($data as $key => $d) {
            $labels[] = $key;
            $dataset[] = $d;
        }

        $chart = $this->chartBuilder->createChart(Chart::TYPE_RADAR);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $project->getFullname(),
                    'backgroundColor' => 'rgba(159,170,174,0.7)',
                    'borderColor' => 'rgb(95,158,160)',
                    'data' => $dataset,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => !empty($dataset) ? max($dataset) : 0
                ],
            ],
        ]);

        return $chart;
    }

    /**
     * Получить активность по всем веткам конкретного репозитория
     * @deprecated
     */
    public function getStorageDataset2(Storage $storage)
    {
        $dates = [];
        $authors = [];

        foreach ($branches as $branch) {
            $branchCommits = $this->client->api('repo')->commits()->all(
                $storage->getAuthor(),
                $storage->getLink(),
                ['sha' => $branch['name']]
            );

            foreach ($branchCommits as $j) {
                $dates[] = $j['commit']['author']['date'];
                $authors[] = $j['commit']['author']['name'];
            }
        }

        // активность по всем веткам всех репозиториев проекта
        $commitCounts = array_count_values($authors);

        $labels = [];
        $dataset = [];
        foreach ($commitCounts as $author => $count) {
            $labels[] = $author;
            $dataset[] = $count;
        }

        $chart = $this->chartBuilder->createChart(Chart::TYPE_RADAR);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $storage->getLink(),
                    'backgroundColor' => 'rgba(75,0,130, 0.5)',
                    'borderColor' => 'rgba((147,112,219, 0.6)',
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

    /**
     * Получить активность по конкретной ветке указанного репозитория
     * @deprecated
     */
    public function getBranchDataset2(Storage $storage, string $sha)
    {
        // ветки по репозиторию
        $authors = [];

        $commits = $this->client->api('repo')->commits()->all(
            $storage->getAuthor(),
            $storage->getLink(),
            ['sha' => $sha]
        );

        foreach ($commits as $commit) {
            $authors[] = $commit['commit']['author']['name'];
        }

        // активность по всем веткам всех репозиториев проекта
        $commitCounts = array_count_values($authors);

        $labels = [];
        $dataset = [];
        $backgroundColors = [];

        $i = 0;
        foreach ($commitCounts as $author => $count) {
            $labels[] =  $author;
            $dataset[] = $count;
            $backgroundColors[] = 'rgb' . self::COLORS[$i++];
        }

        $chart = $this->chartBuilder->createChart(Chart::TYPE_PIE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'symfony',
                    'backgroundColor' => $backgroundColors,
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
            'plugins' => [
                'legend' => [
                    'position' => 'top'
                ],
                'title' => [
                    'display' => 'true',
                    'text' => $sha
                ]
            ]
        ]);

        return $chart;
    }

    /**
     * @todo
     */
    public function test()
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $labels = ['2023-05-21', '2023-05-25', '2023-05-28', '2023-05-29', '2023-06-03', '2023-05-22'];
        $dataset0 = [700, 680, 680, 700, 710, 800];
        $dataset1 = [560, 630, 620, 580, 400, 300];
        $dataset2 = [430, 290, 520, 567, 354, 295];

        $dataset = [];
        $dataset[] = $dataset0;
        $dataset[] = $dataset1;
        $dataset[] = $dataset2;

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'symfony',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $dataset0,
                ],
                [
                    'label' => 'symfony',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $dataset1,
                ],
                [
                    'label' => 'symfony',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $dataset2,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 900,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top'
                ],
                'title' => [
                    'display' => 'true',
                    // 'text' => $sha
                ]
            ]
        ]);

        return $chart;
    }
}
