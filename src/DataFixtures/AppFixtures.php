<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     *d
     */
    public function load(ObjectManager $manager)
    {
       $blogPost = new BlogPost();
       $blogPost->setTitle('A first post!');
       $blogPost->setPublished(new \DateTime('2018-07-01 12:00:00'));
       $blogPost->setContent('Post text!');
       $blogPost->setAuthor('Jay Phillips');
       $blogPost->setSlug('a-first-post');


       $manager->persist($blogPost);

       $blogPost = new BlogPost();
       $blogPost->setTitle('A second post!');
       $blogPost->setPublished(new \DateTime('2018-07-01 12:00:00'));
       $blogPost->setContent('Post text!');
       $blogPost->setAuthor('Jay Phillips');
       $blogPost->setSlug('a-second-post');

       $manager->persist($blogPost);


       $manager->flush();



    }
}
