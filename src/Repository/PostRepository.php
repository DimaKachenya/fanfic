<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    private SessionInterface $session;
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry,EntityManagerInterface $em,SessionInterface $session)
    {
        parent::__construct($registry, Post::class);
        $this->em = $em;

        $this->session=$session;
    }

    public function addPost(Post $post,User $user)
    {
        $post->setUserId($user);
        $post->setAverageMark(0);
        $this->em->persist($post);
        $this->em->flush();
        $post=$this->findOneBy(array('name'=>$post->getName()));
        $this->addPostIdInSession($post);
    }

    public function addPostIdInSession(Post $post)
    {
        $this->session->set('postId',$post->getId());
    }

    public function getCurrentPost():Post
    {
        return $this->findOneBy(array('id'=>$this->session->get('postId')));
    }

    public function getPostAndUserAndAllChaptersByPostId(string $postId): array
    {
        $query="
            select chapter.number_chapter as chapterNumber, post.average_mark as averageMark, user.email as email , post.name as postName, chapter.name as chapterName, chapter.body as chapterBody, chapter.like_counter as likeCounter, chapter.id as id 
            from post
            inner join chapter
            on post.id = chapter.post_id_id
            inner join user
             on post.user_id_id = user.id
            where post.id= '$postId' ;";
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


    public function updateAverageMark(int $mark,string $postId)
    {
        $post=$this->findOneBy(['id'=>$postId]);
        if($post->getAverageMark()!=0) {
            $post->setAverageMark(($post->getAverageMark()+$mark)/2);
        }else{
            $post->setAverageMark($mark);
        }
        $this->em->persist($post);
        $this->em->flush();
    }

    public function deletePost(int $id)
    {
        $sql="delete from post
                where post.id=$id";
        try {
            $stmt = $this->em->getConnection()->prepare($sql);
            $stmt->execute();
        } catch (\Doctrine\DBAL\Exception | Exception $e) {
        }
    }

    public function updatePost(Post $post, int $id)
    {
        $currentPost=$this->find($id);
        $currentPost->setName($post->getName());
        $currentPost->setShortDescription($post->getShortDescription());
        $currentPost->setGenre($post->getGenre());
        $this->em->persist($currentPost);
        $this->em->flush();
    }

}
