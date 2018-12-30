<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 8/11/2018
 * Time: 7:04 PM
 */

namespace App\DataFixtures;


use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private const USERS = [
        [
            'username' => 'elshanakbarov',
            'email' => 'elshanakbarovv@gmail.com',
            'password' => 'united768726',
            'fullName' => 'Elshan Akbarov',
            'roles'=> [User::ROLE_USER]
        ],
        [
            'username' => 'elshanakbarov.admin',
            'email' => 'elshanakbarovv.dev@gmail.com',
            'password' => 'united768726',
            'fullName' => 'Elshan Akbarov',
            'roles'=> [User::ROLE_ADMIN]
        ],
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles'=> [User::ROLE_USER]
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
            'roles'=> [User::ROLE_USER]
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry12345',
            'fullName' => 'Marry Gold',
            'roles'=> [User::ROLE_USER]
        ],
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?',
        'Who is gonna win? Khabib or Conor?',
        'Did you see Messi\'s Hatrick',
        'There\'s a guy named Elshan',
        'I hope time will pass fast and i will see her again',
        'We have dreams together...',
        'Our home,family and we are travelling in our dreams',
        'I Love Her so much',
        'I Really love her so much,now i\'m without her and feel really lost'
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    
    public function __construct (UserPasswordEncoderInterface $passwordEncoder)
    {
    
        $this->passwordEncoder = $passwordEncoder;
    
    }
    
    public function load(ObjectManager $manager)
    {

        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);

    }

    private function loadMicroPosts (ObjectManager $manager)
    {

        for ($i = 0; $i < 30;$i++){

            $microPost = new MicroPost();
            //Setting random text from POST_TEXT arr
            $microPost->setText(
                self::POST_TEXT[rand(0,count(self::POST_TEXT)-1)]
            );

            //Setting different datetime
            $date = new \DateTime();
            $date->modify('-'.rand(0,10).' day');
            $microPost->setTime($date);

            //Setting random users From Users Arr
            $microPost->setUser($this->getReference(
                self::USERS[rand(0,count(self::USERS)-1)]["username"]
            ));

            $manager->persist($microPost);
        }

        $manager->flush();

    }

    public function loadUsers (ObjectManager $manager)
    {
        foreach (self::USERS as $userData){

                $user = new User();

                $user->setUsername($userData["username"]);
                $user->setFullName($userData["fullName"]);
                $user->setEmail($userData["email"]);
                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        $userData["password"]
                    )
                );
                $user->setRoles($userData["roles"]);
                $this->addReference($userData["username"],$user);

                $manager->persist($user);
        }

        $manager->flush();
    }

}