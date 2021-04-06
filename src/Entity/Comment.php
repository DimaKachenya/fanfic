<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="postId")
     */
    private User $userId;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     */
    private Post $postId;

    /**
     * @ORM\Column(type="text")
     */
    private string $text;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPostId(): ?Post
    {
        return $this->postId;
    }

    public function setPostId(?Post $postId): self
    {
        $this->postId = $postId;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getUserId()->getEmail().' '.$this->getText();
    }

}
