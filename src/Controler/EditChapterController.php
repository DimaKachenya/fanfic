<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Form\ChapterType;
use App\Repository\ChapterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditChapterController extends AbstractController
{
    private ChapterRepository $chapterRepository;

    /**
     * EditChapterController constructor.
     * @param ChapterRepository $chapterRepository
     */
    public function __construct(ChapterRepository $chapterRepository)
    {
        $this->chapterRepository = $chapterRepository;
    }

    /**
     * @Route("/edit/chapter/{id}", name="edit_chapter")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function index(Request $request, int $id): Response
    {
        $chapter=new Chapter();
        $chapterForm = $this->createForm(ChapterType::class, $chapter);
        $chapterForm->handleRequest($request);

        if ($chapterForm->isSubmitted() && $chapterForm->isValid()) {
            $this->chapterRepository->updateChapter($chapter, $id);

            return $this->redirectToRoute('edit',["id"=>$id]);
        }

        return $this->render('edit_chapter/index.html.twig', [
           'chapterForm'=>$chapterForm->createView(),
        ]);
    }
}
