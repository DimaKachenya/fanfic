<?php

namespace App\Controller;

use App\Repository\ChapterRepository;
use App\Repository\CommentRepository;
use App\Repository\MarkRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPageController extends AbstractController
{

    private UserRepository $userRepository;
    private PostRepository $postRepository;
    private ChapterRepository $chapterRepository;
    private MarkRepository $markRepository;
    private CommentRepository $commentRepository;

    /**
     * UserPageController constructor.
     * @param UserRepository $userRepository
     * @param PostRepository $postRepository
     * @param ChapterRepository $chapterRepository
     * @param MarkRepository $markRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(UserRepository $userRepository,PostRepository $postRepository, ChapterRepository $chapterRepository ,MarkRepository $markRepository, CommentRepository $commentRepository)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->markRepository = $markRepository;
        $this->commentRepository = $commentRepository;
        $this->chapterRepository = $chapterRepository;
    }


    /**
     * @Route("/user/page", name="user_page")
     */
    public function index(): Response
    {

        if(isset($_POST["delete"])){
            $this->chapterRepository->deleteAllByPostId($_POST["delete"]);
            $this->markRepository->deleteByPostId($_POST["delete"]);
            $this->commentRepository->deleteByPostId($_POST["delete"]);
            $this->postRepository->deletePost($_POST["delete"]);
        }


        $userPosts=$this->userRepository->getAllPostByUserEmail($this->getUser()->getUsername());

        return $this->render('user_page/index.html.twig', [
            'userPosts'=>$userPosts
        ]);
    }
}
