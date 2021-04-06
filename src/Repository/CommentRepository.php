<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Comment::class);
        $this->em=$em;
    }

    public function addComment(Comment $comment)
    {
        $this->em->persist($comment);
        $this->em->flush();
    }

    public function getAllComments(): array
    {
        return $this->findAll();
    }

    public function getAllCommentsByPostId(Post $post): array
    {
        return $this->findBy(['postId'=>$post->getId()]);
    }

    public function deleteByPostId(int $id)
    {
        $sql="delete from comment
                where comment.post_id_id=$id";

        try {
            $stmt = $this->em->getConnection()->prepare($sql);
        } catch (\Doctrine\DBAL\Exception $e) {
        }
        try {
            $stmt->execute();
        } catch (\Doctrine\DBAL\Driver\Exception $e) {
        }
    }
}
