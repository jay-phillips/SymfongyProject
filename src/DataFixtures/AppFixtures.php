<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Comment;

use App\Entity\BlogPost;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @var Faker\Factory
 */


class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.com',
            'name' => 'Piotr Jura',
            'password' => 'secret123#'
            //'roles' => [User::ROLE_SUPERADMIN],
            //'enabled' => true
        ],
        [
            'username' => 'john_doe',
            'email' => 'john@blog.com',
            'name' => 'John Doe',
            'password' => 'secret123#'
            //'roles' => [User::ROLE_ADMIN],
            //'enabled' => true
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob@blog.com',
            'name' => 'Rob Smith',
            'password' => 'secret123#'
            //'roles' => [User::ROLE_WRITER],
            //'enabled' => true
        ],
        [
            'username' => 'jenny_rowling',
            'email' => 'jenny@blog.com',
            'name' => 'Jenny Rowling',
            'password' => 'secret123#'
            //'roles' => [User::ROLE_WRITER],
            //'enabled' => true
        ],
        [
            'username' => 'han_solo',
            'email' => 'han@blog.com',
            'name' => 'Han Solo',
            'password' => 'secret123#'
            //'roles' => [User::ROLE_EDITOR],
            //'enabled' => false
        ],
        [
            'username' => 'jedi_knight',
            'email' => 'jedi@blog.com',
            'name' => 'Jedi Knight',
            'password' => 'secret123#'
            //'roles' => [User::ROLE_COMMENTATOR],
            //'enabled' => true
        ],
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

     $this->passwordEncoder = $passwordEncoder;
     $this->faker = \Faker\Factory::create();
        
    }
    
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */


    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPost($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPost(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++)
        {
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setContent($this->faker->realText());

            $authorReference = $this->getRandomUserReference();

            $blogPost->setAuthor($authorReference);
            $blogPost->setSlug($this->faker->slug);

            $this->setReference("blog_post_$i", $blogPost);

            $manager->persist($blogPost);
        }

        $manager->flush();

    }

    public function loadComments(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++)
        {
          for ($j = 0; $j < rand(1, 10); $j++){
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished($this->faker->dateTimeThisYear);

                $authorReference = $this->getRandomUserReference();

                $comment->setAuthor($authorReference);
                $comment->setBlogPost($this->getReference("blog_post_$i"));

                $manager->persist($comment);
            }

        }

        $manager->flush();

        
    }

    public function loadUsers(ObjectManager $manager)
    {

        foreach(self::USERS as $userFixture){

            $user = new User();
            $user->setUsername($userFixture['username']);
            $user->setEmail($userFixture['email']);
            $user->setName($userFixture['name']);
           
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $userFixture['password']
            ));
    
            $this->addReference('user_' . $userFixture['username'], $user);
    
    
            $manager->persist($user);
            
        }
        $manager->flush();    


       
    }


    protected function getRandomUserReference(): User
    {
        return $this->getReference('user_'.self::USERS[rand(0, 3)]['username']);
    }
}