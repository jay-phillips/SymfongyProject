<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\BlogPost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use App\Repository\CommentRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * 
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ApiResource(
 *       itemOperations={
 *       "get",
 *       "put"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() == user"
 *          }
 *        },
 *      collectionOperations={
 *       "get",
 *       "post"={
 *           "access_control"="is_granted('IS_AUTHENTICATED_FULLY')"
 *        }
 *      }
 * )
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published;


    /**
     * @ORM\ManytoOne(targetEntity="App\Entity\BlogPost", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $blogPost;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): self
    {
       $this->author = $author;

       return $this;
    }

    
    public function getBlogPost(): BlogPost
    {
       return $this->blogPost;

    }

    public function setBlogPost(BlogPost $blogPost): self
    {
        $this->blogPost = $blogPost;

        return $this;
    }
}
