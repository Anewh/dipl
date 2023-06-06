<?php

namespace App\DataFixtures;

use App\Entity\Field;
use App\Entity\Page;
use App\Entity\Project;
use App\Entity\Storage;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    const USERS = [
        [
            'phone' => '89205553535',
            'roles' => ['ROLE_DEV'],
            'email' => 'russ@example.com',
            'password' => 'password',
            'lastname' => 'Иванов',
            'firstname' => 'Руслан',
            'githubName' => 'ruslooob',
            'token' => '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'
        ],
        [
            'phone' => '89209999999',
            'roles' => ['ROLE_DEV'],
            'email' => 'dima@example.com',
            'password' => 'password',
            'lastname' => 'Смирнов',
            'firstname' => 'Дмитрий',
            'githubName' => 'dimasic25',
            'token' => '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'
        ],
        [
            'phone' => '89508008080',
            'roles' => ['ROLE_DEV'],
            'email' => 'evg@example.com',
            'password' => 'password',
            'lastname' => 'Кузнецов',
            'firstname' => 'Евгений',
            'githubName' => 'GrishaginEvgeny',
            'token' => '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'
        ],
        [
            'phone' => '89508234080',
            'roles' => ['ROLE_ADMIN'],
            'email' => 'again@example.com',
            'password' => 'password',
            'lastname' => 'Микаелян',
            'firstname' => 'Анна',
            'githubName' => 'Anewh',
            'token' => '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'
        ],
        [
            'phone' => '89508004560',
            'roles' => ['ROLE_DEV'],
            'email' => 'some@example.com',
            'password' => 'password',
            'lastname' => 'Смирнова',
            'firstname' => 'Дарья',
            'githubName' => 'daria-popova',
            'token' => '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'
        ],
        [
            'phone' => '89578608080',
            'roles' => ['ROLE_USER'],
            'email' => 'owner@example.com',
            'password' => 'password',
            'lastname' => 'Иванов',
            'firstname' => 'Иван',
            'githubName' => 'Anewh',
            'token' => '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'
        ]
        
    ];

    const STORAGES = [ 
        [
            'author' => 'GrishaginEvgeny',
            'description' => ' ',
            'link' => 'IntaroPracticeProject'
        ],
        [
            'author' => 'intaro',
            'description' => ' ',
            'link' => 'symfony-course'
        ],
        [
            'author' => 'Anewh',
            'description' => ' ',
            'link' => 'study-on'
        ],
        [
            'author' => 'daria-popova',
            'description' => ' ',
            'link' => 'symfony-student-practice'
        ],
        [
            'author' => 'daria-popova',
            'description' => ' ',
            'link' => 'symfony-vue'
        ],
        [
            'author' => 'ruslooob',
            'description' => 'habr clone',
            'link' => 'HabrXX'
        ],
        
    ];
    const PROJECTS = [
        [
            'fullName' => 'Практика: Интернет-магазин одежды и продуктов для дома',
            'codeName' => 'shop-practice',
            'type' => 'web'
        ],
        [
            'fullName' => 'Study-on',
            'codeName' => 'Онлайн-сервис для прохождения учебных курсов',
            'type' => 'web'
        ],
        [
            'fullName' => 'Symfony курсы',
            'codeName' => 'Учебный проект для подготовки стажеров к рабочим проектам',
            'type' => 'web'
        ],
        [
            'fullName' => 'My Habr',
            'codeName' => 'Сервис для публикации новостей, аналитических статей, мыслей, связанных с информационными технологиями, бизнесом и интернетом',
            'type' => 'web'
        ],
        
    ];
    const FIELDS = [
        // Поля для проекта Практика - интернет магазин 
        [
            'header' => 'ТЗ на практику: интернет-магазин',
            'content' => 'Требования к практике и к реализуемуму интернет-магазину',
            'type' => 'link',
            'linkName' => 'Задание',
            'link' => 'https://docs.google.com/document/d/1s5sY9SkQ1iTIBCtd8XtN1n_dq6yxCR-tES6qiha_mn8/edit#heading=h.w1r3v6pwapw8'
        ],
        [
            'header' => 'Макет в фигме',
            'content' => 'Примерный дизайн интерфеса. Не обязательно строго следовать каждой детали',
            'type' => 'link',
            'linkName' => 'макет',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Добавление ключей для взаимодействия с API RetailCRM',
            'content' => 'Тут можно добавить API ключ для настройки взаимодействия сервисов',
            'type' => 'link',
            'linkName' => 'retailCRM admin api-keys',
            'link' => 'https://popova.retailcrm.ru/admin/api-keys'
        ],
        [
            'header' => 'Пример запроса для получения заказов из CRM',
            'content' => 'Для осуществления запроса нужно составить ссылку вида: https://{username}.retailcrm.ru/api/v5/orders/115?apiKey={ApiKey}&by=id',
            'type' => 'text',
            'linkName' => ' ',
            'link' => ' '
        ],
        // Study-on
        [
            'header' => 'ТЗ на study-on',
            'content' => 'Задание',
            'type' => 'link',
            'linkName' => 'тз/задание',
            'link' => 'https://docs.google.com/document/d/1TJWj2YThSuLsDviYiXnhQWpCsCBiLUCRCPl5j4NbbKQ/edit'
        ],
        [
            'header' => 'Описание настройки тестового окружения проекта',
            'content' => 'Настройка тестов. Тестовые сценарии указаны в ТЗ',
            'type' => 'link',
            'linkName' => 'tests readme',
            'link' => 'https://github.com/intaro/symfony-course/tree/master/practice/lesson-04'
        ],
        [
            'header' => 'Настройка JWT для взаимодействия с биллинг-системой',
            'content' => 'Кратко о настройке JWT. Детальная информация о биллинг-системе доступа в ТЗ',
            'type' => 'link',
            'linkName' => 'настройка токена',
            'link' => 'https://github.com/intaro/symfony-course/tree/master/practice/lesson-05'
        ],
        [
            'header' => 'Контактное лицо',
            'content' => 'По вопросам обращаться к @ewhii (телеграмм)',
            'type' => 'text',
            'linkName' => ' ',
            'link' => ' '
        ],
        [
            'header' => 'Макет в фигме',
            'content' => 'Примерный дизайн интерфеса. Не обязательно строго следовать каждой детали',
            'type' => 'link',
            'linkName' => 'макет',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Документация пользователя',
            'content' => 'Ссылка на документацию пользователя. По вопросам изменения документации обращаться к @ewhii (телеграмм)',
            'type' => 'link',
            'linkName' => 'user doc',
            'link' => 'https://ru.lipsum.com/'
        ],
        // Symfony курсы
        [
            'header' => 'Документация пользователя',
            'content' => 'Ссылка на документацию пользователя. По вопросам изменения документации обращаться к @ewhii (телеграмм)',
            'type' => 'link',
            'linkName' => 'user doc',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Требования к рефакторингу',
            'content' => 'Описание того, что не следует менять при рефакторинге',
            'type' => 'link',
            'linkName' => 'docs',
            'link' => 'https://docs.google.com/document/d/1FONLTky_D9NRJnrSk_5sZV_Gv39-WsgQ8iJ3QkczFuo/edit'
        ],
        [
            'header' => 'Требования к MVC',
            'content' => 'Что должно быть в минимально работоспособном продукте',
            'type' => 'link',
            'linkName' => 'docs',
            'link' => 'https://docs.google.com/presentation/d/1MBhq9nUMU7sTwwhxOnBG--LETp71STn-D4fufVFrJPU/edit#slide=id.p4'
        ],
        [
            'header' => 'Общая информация',
            'content' => 'ТЗ в упрощенном виде (другого нет)',
            'type' => 'link',
            'linkName' => 'docs',
            'link' => 'https://docs.google.com/presentation/d/1EN0-XX2uJeTBeekN_ZoogckYZ-JC0KxAU_NndeRzkkQ/edit#slide=id.p4'
        ],
        [
            'header' => 'Требования к БД',
            'content' => 'Рекомендации разработчикам',
            'type' => 'link',
            'linkName' => 'docs',
            'link' => 'https://docs.google.com/presentation/d/1PsihPRz3-Vv5XsyupponkgDZvWh_KnTICqyQbwtTXWU/edit#slide=id.p4'
        ],
        [
            'header' => 'Требования к API',
            'content' => 'Основные требования к API',
            'type' => 'link',
            'linkName' => 'docs',
            'link' => 'https://docs.google.com/presentation/d/15Gg2VvcJSq2j2Dy44IkN7u6qKcrJwFNmgS1ZO3r2UPI/edit#slide=id.p4'
        ],
        // My Habr
        [
            'header' => 'Документация пользователя',
            'content' => 'Ссылка на документацию пользователя. По вопросам изменения документации обращаться к @ewhii (телеграмм)',
            'type' => 'link',
            'linkName' => 'user doc',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Макет в фигме',
            'content' => 'Примерный дизайн интерфеса. Не обязательно строго следовать каждой детали',
            'type' => 'link',
            'linkName' => 'макет',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Общая информация',
            'content' => 'ТЗ',
            'type' => 'link',
            'linkName' => 'docs',
            'link' => 'https://docs.google.com/presentation/d/1EN0-XX2uJeTBeekN_ZoogckYZ-JC0KxAU_NndeRzkkQ/edit#slide=id.p4'
        ],
        [
            'header' => 'Use cases',
            'content' => 'Пути пользователя',
            'type' => 'link',
            'linkName' => 'docs',
            'link' => 'https://docs.google.com/presentation/d/1PsihPRz3-Vv5XsyupponkgDZvWh_KnTICqyQbwtTXWU/edit#slide=id.p4'
        ]
        
    ];


    private UserPasswordHasherInterface $userPasswordHashed;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHashed,
    ) {
        $this->userPasswordHashed = $userPasswordHashed;
    }

    
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $users = [];

        foreach (self::USERS as $i => $value) {
            $user = (new User())
                ->setEmail($value['email'])
                ->setPhone($value['phone'])
                ->setRoles($value['roles'])
                //->setPassword()
                ->setFirstname($value['firstname'])
                ->setLastname($value['lastname'])
                ->setGithubName($value['githubName'])
                ->setToken($value['token']);
            $password = $this->userPasswordHashed->hashPassword($user, $value['password']);
            $user->setPassword($password);
            array_push($users, $user);
            //$manager->persist($user);
        }


        // $storage = (new Storage())
        // ->setDescription(self::STORAGE['description'])
        // ->setLink(self::STORAGE['link'])
        // ->setAuthor('GrishaginEvgeny');

        $fields = [];

        foreach (self::FIELDS as $i => $value) {
            $field = (new Field())
                ->setHeader($value['header'])
                ->setContent($value['content'])
                ->setType($value['type'])
                ->setLink($value['link'])
                ->setLinkName($value['linkName']);
            array_push($fields, $field);
            //$manager->persist($field);
        }

        $projects = [];

        foreach (self::PROJECTS as $i => $value) {
            $project = (new Project())
                ->setFullName($value['fullName'])
                ->setCodeName($value['codeName'])
                ->setType($value['type']);
                // ->addStorage($storage);
            // foreach ($fields as $field) {
            //     $project->addField($field);
            //     //$field->setProject($project);
            // }
            // foreach ($fields as $field) {
            //     $project->addField($field);
            //     //$field->setProject($project);
            // }
            // foreach ($fields as $field) {
            //     $project->addField($field);
            //     //$field->setProject($project);
            // }
            array_push($projects, $project);
        }

        $team1 = (new Team())->addUser($users[2])->addUser($users[3])->addUser($users[4])->setName('Team 1');
        $team2 = (new Team())->addUser($users[0])->addUser($users[1])->setName('Team 2');

        $projects[0]->addTeam($team1);
        $projects[1]->addTeam($team1);
        $projects[2]->addTeam($team1);
        $projects[2]->addTeam($team2);
        $projects[3]->addTeam($team2);
        $projects[3]->addUser($users[5]);
        //$projects[3]->addStorage($storage);

        $storages = [];
        foreach (self::STORAGES as $i => $value) {
            $storage = (new Storage())
                ->setDescription($value['description'])
                ->setLink($value['link'])
                ->setAuthor($value['author']);
            array_push($storages, $storage);
        }


        $projects[0]->addStorage($storages[0]);
        $projects[1]->addStorage($storages[1]);
        $projects[1]->addStorage($storages[2]);
        $projects[2]->addStorage($storages[3]);
        $projects[2]->addStorage($storages[4]);
        $projects[3]->addStorage($storages[5]);

        for($i = 0; $i < 4; $i++){
            $projects[0]->addField($fields[$i]);
        }

        for($i = 4; $i < 10; $i++){
            $projects[1]->addField($fields[$i]);
        }

        for($i = 10; $i < 16; $i++){
            $projects[2]->addField($fields[$i]);
        }

        for($i = 16; $i < 20; $i++){
            $projects[3]->addField($fields[$i]);
        }


        $manager->persist($team1);
        $manager->persist($team2);
        //$manager->persist($storage);
        
        
        foreach($projects as $elem){
            $manager->persist($elem);
        }

        foreach($users as $elem){
            $manager->persist($elem);
        }

        foreach($fields as $elem){
            $manager->persist($elem);
        }

        foreach($storages as $elem){
            $manager->persist($elem);
        }

        foreach($fields as $elem){
            $manager->persist($elem);
        }
        // foreach (self::COURCES as $i => $value) {
        //     $course = (new Course())
        //         ->setCode($value['code'])
        //         ->setName($value['name'])
        //         ->setDescription($value['description']);
        //     $manager->persist($course);

        //     foreach (self::LESSONS[$i] as $k => $lessonData) {
        //         $lesson = (new Lesson())
        //             ->setCourse($course)
        //             ->setName($lessonData['name'])->setContent($lessonData['content'])
        //             ->setSerial($k);
        //         $manager->persist($lesson);
        //     }
        // }
        $parentPage = (new Page())->setFile("- __[pica](https://nodeca.github.io/pica/demo/)__ - high quality and fast image resize in browser.")->setProject($projects[3])->setHeader('Example parent page');
        $childPage1 =  (new Page())->setFile("> Blockquotes can also be nested...")->setParent($parentPage)->setProject($projects[3])->setHeader('Example child page 1');
        $childPage2 =  (new Page())->setFile("![Minion](https://octodex.github.com/images/minion.png)")->setParent($parentPage)->setProject($projects[3])->setHeader('Example child page 2');
        $childPage3 =  (new Page())->setFile("> Shortcuts (emoticons): :-) :-( 8-) ;)")->setParent($parentPage)->setProject($projects[3])->setHeader('Example child page 3');
        

        $manager->persist($parentPage);
        $manager->persist($childPage1);
        $manager->persist($childPage2);
        $manager->persist($childPage3);
        

        $manager->flush();
    }

    // public function load(ObjectManager $manager): void
    // {
    //     $user = new User();
    //     $password = $this->userPasswordHashed->hashPassword(
    //         $user,
    //         'password'
    //     );
    //     $user
    //         ->setEmail('user@example.com')
    //         ->setPassword($password)
    //         ->setBalance(1000.0);

    //     $manager->persist($user);

    //     $admin = new User();
    //     $password = $this->userPasswordHashed->hashPassword(
    //         $admin,
    //         'password'
    //     );
    //     $admin
    //         ->setEmail('admin@example.com')
    //         ->setPassword($password)
    //         ->setRoles(['ROLE_SUPER_ADMIN'])
    //         ->setBalance(1000.0);

    //     $manager->persist($admin);

    //     $manager->flush();
    // }
}
