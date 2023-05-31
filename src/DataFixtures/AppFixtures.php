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
            'lastname' => 'Миладзе',
            'firstname' => 'Валерий',
            'githubName' => 'Anewh',
            'token' => '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'
        ]
    ];

    const STORAGE = [
        'description' => 'Проект по практике ',
        'link' => 'IntaroPracticeProject'
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
            'content' => 'Описание ссылки. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'type' => 'link',
            'linkName' => 'example link',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Просто файлик для примера',
            'content' => 'Описание файла. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ',
            'type' => 'file',
            'linkName' => 'example file',
            'link' => ''
        ],
        [
            'header' => 'Просто поле с текстом',
            'content' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas',
            'type' => 'text',
            'linkName' => '',
            'link' => ''
        ],
        [
            'header' => 'Просто ссылка для примера',
            'content' => 'Описание ссылки. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ',
            'type' => 'link',
            'linkName' => 'example link',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Просто ссылка для примера',
            'content' => 'Описание ссылки. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'type' => 'link',
            'linkName' => 'example link',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Просто файлик для примера',
            'content' => 'Описание файла. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ',
            'type' => 'file',
            'linkName' => 'example file',
            'link' => ''
        ],
        [
            'header' => 'Просто поле с текстом',
            'content' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas',
            'type' => 'text',
            'linkName' => '',
            'link' => ''
        ],
        [
            'header' => 'Просто ссылка для примера',
            'content' => 'Описание ссылки. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ',
            'type' => 'link',
            'linkName' => 'example link',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Просто ссылка для примера',
            'content' => 'Описание ссылки. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'type' => 'link',
            'linkName' => 'example link',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Просто файлик для примера',
            'content' => 'Описание файла. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ',
            'type' => 'file',
            'linkName' => 'example file',
            'link' => ''
        ],
        [
            'header' => 'Просто поле с текстом',
            'content' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas',
            'type' => 'text',
            'linkName' => '',
            'link' => ''
        ],
        [
            'header' => 'Просто ссылка для примера',
            'content' => 'Описание ссылки. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ',
            'type' => 'link',
            'linkName' => 'example link',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Просто ссылка для примера',
            'content' => 'Описание ссылки. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'type' => 'link',
            'linkName' => 'example link',
            'link' => 'https://ru.lipsum.com/'
        ],
        [
            'header' => 'Просто файлик для примера',
            'content' => 'Описание файла. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ',
            'type' => 'file',
            'linkName' => 'example file',
            'link' => ''
        ],
        [
            'header' => 'Просто поле с текстом',
            'content' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas',
            'type' => 'text',
            'linkName' => '',
            'link' => ''
        ],
        [
            'header' => 'Просто ссылка для примера',
            'content' => 'Описание ссылки. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ',
            'type' => 'link',
            'linkName' => 'example link',
            'link' => 'https://ru.lipsum.com/'
        ],
        
        
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
        ->setLink(self::STORAGE['link'])
        ->setAuthor('GrishaginEvgeny');

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
            foreach ($fields as $field) {
                $project->addField($field);
                //$field->setProject($project);
            }
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
