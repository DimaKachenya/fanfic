<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private EntityManagerInterface $em;
    private SessionInterface $session;

    public function __construct(ManagerRegistry $registry,UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em,SessionInterface $session )
    {
        parent::__construct($registry, User::class);
        $this->passwordEncoder = $passwordEncoder;
        $this->em=$em;
        $this->session=$session;

    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function add(User $user){
        // Encode the new users password
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        // Set their role
        $user->setRoles(['ROLE_USER']);
        // Save
        $this->em->persist($user);
        $this->em->flush();
    }

    public function allPostWithUsers(): array
    {
        $query ="
            select post.id, user.email , post.name, post.short_description ,post.genre 
            from user ,post
            where user.id = post.user_id_id;
            ";

        try {
            $st = $this->em->getConnection()->prepare($query);
            $st->execute();
            //  var_dump($st->fetchAll());
            return $st->fetchAll();
        } catch (Exception | \Doctrine\DBAL\Driver\Exception $e) {
        }
    }


    public function getUser($email):User
    {
        return $this->findOneBy(array('email'=>$email));
    }

    public function getAllPostByUserEmail(string $email): array
    {
        $query ="
            select post.id as postId, post.name as postName, post.genre as postGenre, post.short_description as postShortDescription
            from user
            inner join post on user.id = post.user_id_id
            where user.email = '$email' ;
            ";
        try {
            $st = $this->em->getConnection()->prepare($query);
        } catch (\Doctrine\DBAL\Exception $e) {
        }
        try {
            $st->execute();
        } catch (\Doctrine\DBAL\Driver\Exception $e) {
        }
        return $st->fetchAll();

    }


}
