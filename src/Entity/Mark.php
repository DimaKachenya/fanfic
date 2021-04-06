<?php

namespace App\Entity;

use App\Repository\MarkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MarkRepository::class)
 */
class Mark
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int  $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $mark;


    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="marks")
     * @ORM\JoinColumn(nullable=false)
     */
    private Post $postId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="marks")
     */
    private User $userId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark(): ?int
    {
        return $this->mark;
    }

    public function setMark(int $mark): self
    {
        $this->mark = $mark;

        return $this;
    }


    public function getPostId(): ?Post
    {
        return $this->postId;
    }

    public function setPostId(?Post $postId): self
    {
        $this->postId = $postId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
