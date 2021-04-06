<?php

namespace App\Repository;

use App\Entity\Chapter;
use App\Entity\Like;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Like|null find($id, $lockMode = null, $lockVersion = null)
 * @method Like|null findOneBy(array $criteria, array $orderBy = null)
 * @method Like[]    findAll()
 * @method Like[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $em)
    {
        parent::__construct($registry, Like::class);
        $this->em=$em;
    }

    public function addLike(User $user, Chapter $chapter)
    {
        $like=new Like();
        $like->setUserId($user);
        $like->setChapterId($chapter);
        $like->setIsLike(true);
        $this->em->persist($like);
        $this->em->flush();
    }
}
