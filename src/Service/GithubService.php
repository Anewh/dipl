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

    // отобразить статистику для массива репозиториев
    //TODO - норм проверка на то, что токен пользователя задан
    //TODO - заглушку убрать
    public function getStoragesData($project, $user)
    {
        $client = Client::createWithHttpClient(new HttplugClient());
        $team = $project->getTeams();
        //$client->authenticate($user->getToken(), null, \Github\AuthMethod::ACCESS_TOKEN);
        $client->authenticate('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', null, \Github\AuthMethod::ACCESS_TOKEN);
        
        $storages = $project->getStorage();

        //dd($storages);
        $branches = [];
        //получить все ветки для всех репозиториев проекта

        // foreach($storages as $s){
        //     array_push($repositories, $client->api('repo')->branches($user->getGithubName(), $s->getLink()));
        // }
        
        $branches  = $client->api('repo')->branches($user->getGithubName(), 'diplom_app');
        $commits = $client->api('repo')->commits()->all('Terqaz', 'diplom_app', array('sha' => 'master'));
        //dd($commits);

        

        //$branches = $client->api('repo')->branches('GrishaginEvgeny', 'IntaroPracticeProject');
        // dd($branches);
        // получить активность по всем репозиториям проекта
        //$storages = $project->getStorage();
      
      
        // dd($storages);
        // foreach($storages as $storage){

        // }

        // даты 
        // 

        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $chart;

        
        // foreach($projects as $project){
        //     array_push($storages, $project->getStorages());
        // }
    }

    public function getStoragesByTeams(array $projects)
    {

    }
    
    public function getCommitsByInterval(Storage $storage, $data)
    {

    }

    public function getCommitsByMessage(string $message)
    {

    }

    public function test()
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $chart;
        // return $this->render('home/index.html.twig', [
        //     'chart' => $chart,
        // ]);
    }


    // public function auth(array $credentials): array
    // {

    //     $response = $this->jsonRequest(
    //         self::POST,
    //         '/auth',
    //         [],
    //         $credentials
    //     );
    //     if ($response['code'] === 401) {
    //         throw new BillingUnavailableException('Неправильные логин или пароль');
    //     }
    //     if ($response['code'] >= 400) {
    //         throw new BillingUnavailableException('Сервис временно недоступен');
    //     }
    //     return $this->parseJsonResponse($response);
    // }

    // public function register(array $credentials): array
    // {
    //     $response = $this->jsonRequest(
    //         self::POST,
    //         '/register',
    //         [],
    //         $credentials
    //     );

    //     if ($response['code'] === 409) {
    //         throw new CustomUserMessageAuthenticationException('Пользователь с указанным email уже существует');
    //     }
    //     if ($response['code'] >= 400) {
    //         throw new BillingUnavailableException('Сервис временно недоступен. Попробуйте зарегистрироваться позднее');
    //     }
    //     return $this->parseJsonResponse($response);
    // }

    // public function getCurrentUser(string $token): UserDto
    // {
    //     $response = $this->jsonRequest(
    //         self::GET,
    //         '/users/current',
    //         [],
    //         [],
    //         ['Authorization' => 'Bearer ' . $token]
    //     );

    //     if ($response['code'] === 401) {
    //         throw new UnauthorizedHttpException('Необходимо войти заново');
    //     }

    //     if ($response['code'] >= 400) {
    //         throw new BillingUnavailableException();
    //     }

    //     $userDto = $this->parseJsonResponse($response, UserDto::class);
    //     return $userDto;
    // }

    // private function parseJsonResponse(array $response, ?string $type = null)
    // {
    //     if (null === $type) {
    //         return json_decode($response['body'], true, 512, JSON_THROW_ON_ERROR);
    //     }
    //     return $this->serializer->deserialize($response['body'], $type, 'json');
    // }

    // private function jsonRequest(
    //     string $method,
    //     string $path,
    //     array $parameters = [],
    //     $data = [],
    //     array $headers = []
    // ): array {

    //     $headers['Accept'] = 'application/json';
    //     $headers['Content-Type'] = 'application/json';
    //     $body = $this->serializer->serialize($data, 'json');

    //     if (count($parameters) > 0) {
    //         $path .= '?';

    //         $newParameters = [];
    //         foreach ($parameters as $name => $value) {
    //             $newParameters[] = $name . '=' . $value;
    //         }
    //         $path .= implode('&', $newParameters);
    //     }

    //     $ch = curl_init($this->host . $path);

    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    //     if ($method === self::POST && !empty($body)) {
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    //     }

    //     if (count($headers) > 0) {
    //         $curlHeaders = [];
    //         foreach ($headers as $name => $value) {
    //             $curlHeaders[] = $name . ': ' . $value;
    //         }
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
    //     }
    //     $response = curl_exec($ch);
    //     if (curl_error($ch)) {
    //         throw new BillingUnavailableException(curl_error($ch));
    //     }
    //     $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    //     curl_close($ch);
    //     return [
    //         'code' => $responseCode,
    //         'body' => $response,
    //     ];
    // }
}
