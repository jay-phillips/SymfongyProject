<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\RequestContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
   /* private const POSTS = [
        [
            'id' => '1',
            'slug' => 'hello-world',
            'title' => 'Hello world',
        ],
        [
            'id' => '2',
            'slug' => 'another-post',
            'title' => 'This is another post!',
        ],
        [
            'id' => '3',
            'slug' => 'last-example',
            'title' => 'This is the last example',
        ]
    ];
    */
    /**
     * @Route("/{page}", name ="blog_list", defaults={"page": 5}, requirements={"page"="\d+"}, methods={"GET"})
     */

     public function list($page = 1, Request $request): response
     {

      $limit = $request->get('limit', 10);
      $repository = $this->getDoctrine()->getRepository(BlogPost::class);
      $items = $repository->findAll();

      $response = new Response($limit);

     

       return $this->json(
           [
               'page' => $page,
               'limit' => $limit,
               'data' => array_map(function(BlogPost $item){
                   return $this->generateUrl('blog_by_slug', ['slug' => $item->getSlug()]);
               }, $items)

           ]
          );
     }

     /**
     * @Route("/post/{id}", name ="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost")
     */
     public function post($post): response
     {
        // It's the same as doing find($id) on repository
        return $this->json($post);

     }
     //Alternatively
    // public function post($id){

    //     return $this->json(
    //         $this->getDoctrine()->getRepository(BlogPost::class)->find($id)
    //     );

    // }

    /**
     * @Route("/post/{slug}", name ="blog_by_slug", methods={"GET"})
     * The below annotation is not required when $post is typehinted with BlogPost
     * and route parameter name matches any field on the BlogPost entity
     * @ParamConverter("post", class="App:BlogPost", options={"mapping": {"slug": "slug"}})
     */
    public function postBySlug($post)
    {
        // $this->getDoctrine()->getRepository(BlogPost::class)->findOneBy(['slug' => $slug])
        return $this->json($post);
    }
    // Alternatively
    // public function postBySlug($slug)
    // {
        
    //     return $this->json(
    //         $this->getDoctrine()->getRepository(BlogPost::class)->findOneBy(['slug' => $slug])
    //     );
    // }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request): response
    {
        /** @var Serializer $serilizer */

        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
     */

    public function delete(BlogPost $post): response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }
}

