<?php

namespace App\Repository;

use App\Entity\Chapter;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method Chapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chapter[]    findAll()
 * @method Chapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterRepository extends ServiceEntityRepository
{
    private SessionInterface $session;
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry,EntityManagerInterface $em,SessionInterface $session)
    {
        parent::__construct($registry, Post::class);
        $this->em = $em;
        $this->session=$session;
    }

    public function addChapter(Chapter $chapter,Post $post)
    {
        $chapter->setNumberChapter($this->session->get('chapterNumber'));
        $chapter->setPostId($post);
        $this->em->persist($chapter);
        $this->em->flush();
        $this->session->set('chapterNumber',$chapter->getNumberChapter()+1);
    }


    public function updateLikes(?Chapter $chapter)
    {
        $chapter->setLikeCounter($chapter->getLikeCounter()+1);
        $this->em->persist($chapter);
        $this->em->flush();
    }

    public function findChapterById($id): array
    {
        $query="  select * from chapter
                where id=$id; ";
        try {
            $st = $this->em->getConnection()->prepare($query);
        } catch (\Doctrine\DBAL\Exception $e) {
        }
        try {
            $st->execute();
        } catch (Exception $e) {
        }

        return $st->fetchAll();
    }

    public function takeChapter($id, Post $post):Chapter
    {
        $chapterByQuery=$this->findChapterById($id);
        $chapter=new Chapter();
        $chapter->setLikeCounter($chapterByQuery[0]["like_counter"]);
        $chapter->setPostId($post);
        $chapter->setNumberChapter($chapterByQuery[0]["number_chapter"]);
        $chapter->setName($chapterByQuery[0]["name"]);
        $chapter->setBody($chapterByQuery[0]["body"]);
        $chapter->setId($id);

        return $this->find($id);

    }

    public function deleteAllByPostId(int $id)
    {
        $sql="delete from chapter
                where chapter.post_id_id=$id";
        try {
            $stmt = $this->em->getConnection()->prepare($sql);
            $stmt->execute();
        } catch (\Doctrine\DBAL\Exception | Exception $e) {
        }

    }

    public function updateChapter(Chapter $chapter, int $id)
    {
        var_dump($id);

        $currentChapter=$this->find($id);
        var_dump($currentChapter);
        $currentChapter->setName($chapter->getName());
        $currentChapter->setBody($chapter->getBody());
        $this->em->persist($currentChapter);
        $this->em->flush();
    }
}
