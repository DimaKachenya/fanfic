<?php

namespace App\Repository;

use App\Entity\Mark;
use App\Entity\Post;
use App\Entity\User;
use Cassandra\Exception;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mark[]    findAll()
 * @method Mark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarkRepository extends ServiceEntityRepository
{

    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Mark::class);
        $this->em=$em;
    }

    public function addMark(Mark $mark)
    {
        $this->em->persist($mark);
        $this->em->flush();
    }

    public function userHasRated(User $user, Post $post):bool
    {
        $mark=$this->findOneBy(
            array(
                'userId'=>$user->getId(),
                'postId'=>$post->getId()
            )
        );

        if($mark){
            return true;
        }else{
            return false;
        }
    }

    public function deleteByPostId(int $id)
    {
        $sql="delete from mark
                where mark.post_id_id=$id";

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
