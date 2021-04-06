<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Post;
use App\Form\ChapterType;
use App\Form\PostType;
use App\Repository\ChapterRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private ChapterRepository $chapterRepository;
    private PostRepository $postRepository;
    private SessionInterface $session;
    private UserRepository $userRepository;

    /**
     * PostController constructor.
     * @param ChapterRepository $chapterRepository
     * @param PostRepository $postRepository
     * @param SessionInterface $session
     * @param UserRepository $userRepository
     */
    public function __construct(ChapterRepository $chapterRepository, PostRepository $postRepository,SessionInterface $session,UserRepository $userRepository)
    {
        $this->chapterRepository = $chapterRepository;
        $this->postRepository = $postRepository;
        $this->session=$session;
        $this->userRepository=$userRepository;
    }

    /**
     * @Route("/post", name="post")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $chapter=new Chapter();
        $post=new Post();
        $user=$this->userRepository->findOneBy(['email'=>$this->getUser()->getUsername()]);

        $postForm = $this->createForm(PostType ::class, $post);
        $postForm->handleRequest($request);

        $chapterForm = $this->createForm(ChapterType::class, $chapter);
        $chapterForm->handleRequest($request);



        if ($postForm->isSubmitted() && $postForm->isValid() && $chapterForm->isSubmitted() && $chapterForm->isValid()) {
            $this->postRepository->addPost($post , $user);
            $this->session->set('chapterNumber',0);
            $this->chapterRepository->addChapter($chapter,$this->postRepository->getCurrentPost());
            return $this->redirect('/chapter');
        }

        return $this->render('post/index.html.twig', [
            'postForm' => $postForm->createView(),
            'chapterForm'=> $chapterForm->createView(),
        ]);
    }
}
