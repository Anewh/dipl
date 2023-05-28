<?php

namespace App\DataFixtures;

use App\Entity\Field;
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
            'roles' => ['ROLE_USER'],
            'email' => 'user@example.com',
            'password' => 'password',
            'lastname' => 'Иванов',
            'firstname' => 'Иван',
            'githubName' => 'exampleName',
            'token' => 'example'
        ],
        [
            'phone' => '89209999999',
            'roles' => ['ROLE_USER'],
            'email' => 'some@example.com',
            'password' => 'password',
            'lastname' => 'Валя',
            'firstname' => 'Петрова',
            'githubName' => 'validatorValya',
            'token' => 'example'
        ],
        [
            'phone' => '89508008080',
            'roles' => ['ROLE_ADMIN'],
            'email' => 'again@example.com',
            'password' => 'password',
            'lastname' => 'Холин',
            'firstname' => 'Владимир',
            'githubName' => 'Terqaz',
            'token' => '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'
        ]
    ];

    const STORAGE = [
        'description' => 'Диплом и работа с ботами',
        'link' => 'diplom_app'
    ];
    const PROJECTS = [
        [
            'fullName' => 'какой-то там модуль',
            'codeName' => 'шавуха',
            'type' => 'module'
        ],
        [
            'fullName' => 'какой-то там лендинг',
            'codeName' => 'singlepage',
            'type' => 'landing'
        ],
        [
            'fullName' => 'какой-то фронт для мобилки со стороны',
            'codeName' => 'очередная лагучая херня',
            'type' => 'mobile'
        ],
        [
            'fullName' => 'Диплом чат-боты',
            'codeName' => 'чужой проект для показа тестов',
            'type' => 'web'
        ],
        
    ];
    const FIELDS = [
        [
            'header' => 'Просто ссылка для примера',
            'content' => 'Описание ссыки. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'type' => 'link',
            'linkName' => 'example link',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Просто файлик для примера',
            'content' => 'Описание файла. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'type' => 'file',
            'linkName' => 'example file',
            'link' => ''
        ],
        [
            'header' => 'Просто поле с текстом',
            'content' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.',
            'type' => 'text',
            'linkName' => '',
            'link' => ''
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


        $storage = (new Storage())
        ->setDescription(self::STORAGE['description'])
        ->setLink(self::STORAGE['link']);

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
                ->setType($value['type'])
                ->addStorage($storage);
            foreach ($fields as $field) {
                $project->addField($field);
                //$field->setProject($project);
            }
            array_push($projects, $project);
        }

        $team1 = (new Team())->addUser($users[0])->setName('Team 1');
        $team2 = (new Team())->addUser($users[1])->addUser($users[2])->setName('Team 2');

        $projects[0]->addTeam($team1);
        $projects[1]->addTeam($team1);
        $projects[2]->addTeam($team1);

        $projects[3]->addTeam($team2);
        $projects[3]->addStorage($storage);


        $manager->persist($team1);
        $manager->persist($team2);
        $manager->persist($storage);
        
        
        foreach($projects as $elem){
            $manager->persist($elem);
        }

        foreach($users as $elem){
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
