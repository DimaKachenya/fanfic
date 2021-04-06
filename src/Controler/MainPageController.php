<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainPageController extends AbstractController
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;


    /**
     * MainPageController constructor.
     * @param PostRepository $postRepository
     * @param UserRepository $userRepository

     */
    public function __construct(PostRepository $postRepository,UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;

    }

    /**
     * @Route("/main/page", name="main_page")
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('main_page/index.html.twig', [
            'allPosts'=>$this->userRepository->allPostWithUsers(),
        ]);
    }
}
