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
    private $faker;

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
        $user = $this->getReference('user_admin');

        for ($i = 0; $i < 100; $i++)
        {
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setAuthor($user);
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
          for ($j = 0; $j < rand(1,10); $j++){
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setAuthor($this->getReference('user_admin'));
                $comment->setBlogPost($this->getReference("blog_post_$i"));

                $manager->persist($comment);
            }

        }

        $manager->flush();

        
    }

    public function loadUsers(ObjectManager $manager)
    {

        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@blog.com');
        $user->setName('Jay Phillips');
       
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user
            ,'p@ssw0rd'));

        $this->addReference('user_admin', $user);


        $manager->persist($user);

        $manager->flush();
        
    }
}