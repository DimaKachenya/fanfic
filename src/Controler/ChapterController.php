<?php

namespace App\Controller;

use App\Entity\Chapter;

use App\Form\ChapterType;

use App\Repository\ChapterRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChapterController extends AbstractController
{
    private ChapterRepository $chapterRepository;
    private PostRepository $postRepository;

    /**
     * ChapterController constructor.
     * @param ChapterRepository $chapterRepository
     * @param PostRepository $postRepository
     */
    public function __construct(ChapterRepository $chapterRepository, PostRepository $postRepository)
    {
        $this->chapterRepository = $chapterRepository;
        $this->postRepository=$postRepository;
    }


    /**
     * @Route("/chapter", name="chapter")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $chapter=new Chapter();
        $chapterForm = $this->createForm(ChapterType::class, $chapter);
        $chapterForm->handleRequest($request);

        if ($chapterForm->isSubmitted() && $chapterForm->isValid()) {
            $this->chapterRepository->addChapter($chapter,$this->postRepository->getCurrentPost());
            return $this->render('chapter/index.html.twig', [
                'chapterForm'=>$chapterForm->createView(),
            ]);
        }

        return $this->render('chapter/index.html.twig', [
            'chapterForm'=>$chapterForm->createView(),
        ]);
    }
}
