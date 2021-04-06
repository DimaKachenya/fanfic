<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditPostController extends AbstractController
{
    private PostRepository $postRepository;

    /**
     * EditPostController constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/edit/post/{id}", name="edit_post")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function index(Request $request, int $id): Response
    {
        $post=new Post();
        $postForm=$this->createForm(PostType::class,$post);
        $postForm->handleRequest($request);
        if($postForm->isSubmitted() && $postForm->isValid()){
            $this->postRepository->updatePost($post, $id);

            return $this->redirectToRoute('edit',["id"=>$id]);
        }

        return $this->render('edit_post/index.html.twig', [
            'postForm'=>$postForm->createView(),
        ]);
    }
}
