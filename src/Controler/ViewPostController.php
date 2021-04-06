<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Mark;
use App\Form\CommentType;
use App\Form\MarkType;
use App\Repository\ChapterRepository;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\MarkRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewPostController extends AbstractController
{
    private PostRepository $postRepository;
    private ChapterRepository $chapterRepository;
    private UserRepository $userRepository;
    private MarkRepository $markRepository;
    private CommentRepository $commentRepository;
    private LikeRepository $likeRepository;

   /**
     * ViewPostController constructor.
     * @param PostRepository $postRepository
     * @param ChapterRepository $chapterRepository
     * @param UserRepository $userRepository
     * @param MarkRepository $markRepository
     * @param CommentRepository $commentRepository
     * @param LikeRepository $likeRepository
     */
    public function __construct(PostRepository $postRepository,ChapterRepository $chapterRepository,UserRepository $userRepository, MarkRepository $markRepository, CommentRepository $commentRepository,LikeRepository $likeRepository )
    {
        $this->postRepository = $postRepository;
        $this->chapterRepository = $chapterRepository;
        $this->userRepository=$userRepository;
        $this->markRepository=$markRepository;
        $this->commentRepository=$commentRepository;
        $this->likeRepository=$likeRepository;
    }

    /**
     * @Route("view/post/{id}", name="view_post")
     * @param Request $request
     * @param String $id
     * @return Response
     */
    public function index(Request $request, String $id): Response
    {
        $chapters= $this->postRepository->getPostAndUserAndAllChaptersByPostId($id);

        if($chapters==null){
            var_dump("fatalError");
            return $this->render('not_found/index.html.twig');

        }else{
            $userEmail=$chapters[0]["email"];
            $postName=$chapters[0]["postName"];
            $averageMark=$chapters[0]["averageMark"];
            $user=$this->userRepository->findOneBy(['email'=>$this->getUser()->getUsername()]);
            $post=$this->postRepository->findOneBy(['id'=>$id]);
            $comments=$this->commentRepository->getAllCommentsByPostId($post);

            $comment=new Comment();
            $commentForm=$this->createForm(CommentType::class,$comment);
            $commentForm->handleRequest($request);

            $mark=new Mark();
            $markForm=$this->createForm(MarkType::class,$mark);
            $markForm->handleRequest($request);


            if(isset($_POST["like"])){
                $chapter=$this->chapterRepository->takeChapter($_POST["like"], $post);
                var_dump($chapter);
                die();
                $this->likeRepository->addLike($user,$chapter);
                $this->chapterRepository->updateLikes($chapter);
            }

            if($commentForm->isSubmitted() && $commentForm->isValid()){
                $comment->setPostId($post);
                $comment->setUserId($user);
                $this->commentRepository->addComment($comment);
            }

            if($markForm->isSubmitted() && $markForm->isValid()) {
                $mark->setPostId($post);
                $mark->setUserId($user);
                $this->markRepository->addMark($mark);
                $this->postRepository->updateAverageMark($mark->getMark(), $id);
            }

            return $this->render('view_post/index.html.twig', [
                'postName'=>$postName,
                'userEmail'=>$userEmail,
                'chapters'=>$chapters,
                'averageMark'=>$averageMark,
                'userHasRated'=>$this->markRepository->userHasRated($user,$post),
                'comments'=>$comments,
                'commentForm'=>$commentForm->createView(),
                'markForm'=>$markForm->createView(),
            ]);
        }
    }
}
