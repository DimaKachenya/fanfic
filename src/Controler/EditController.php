<?php

namespace App\Controller;

use App\Repository\ChapterRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends AbstractController
{
    private PostRepository $postRepository;
    private ChapterRepository $chapterRepository;

    /**
     * EditController constructor.
     * @param PostRepository $postRepository
     * @param ChapterRepository $chapterRepository
     */
    public function __construct(PostRepository $postRepository, ChapterRepository $chapterRepository)
    {
        $this->postRepository = $postRepository;
        $this->chapterRepository = $chapterRepository;
    }


    /**
     * @Route("/edit/{id}", name="edit")
     * @param int $id
     * @return Response
     */
    public function index(int $id): Response
    {

       $post=$this->postRepository->find($id);
       if($post!=null) {
           if ($post->getUserId()->getEmail() == $this->getUser()->getUsername()) {

                $chapters=$this->postRepository->getPostAndUserAndAllChaptersByPostId($id);

               return $this->render('edit/index.html.twig', [
                  'chapters'=>$chapters,
                   'post'=>$post,

               ]);
           }
       }

            return $this->render('not_found/index.html.twig');
    }
}
