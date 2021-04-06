<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeRepository::class)
 * @ORM\Table(name="`like`")
 */
class Like
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isLike;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $userId;

    /**
     * @ORM\ManyToOne(targetEntity=Chapter::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private Chapter $chapterId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsLike(): ?bool
    {
        return $this->isLike;
    }

    public function setIsLike(bool $isLike): self
    {
        $this->isLike = $isLike;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getChapterId(): Chapter
    {
        return $this->chapterId;
    }

    public function setChapterId(Chapter $chapterId): self
    {
        $this->chapterId = $chapterId;

        return $this;
    }
}
