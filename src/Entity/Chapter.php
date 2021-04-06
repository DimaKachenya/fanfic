<?php

namespace App\Entity;

use App\Repository\ChapterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChapterRepository::class)
 */
class Chapter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     */
    private string $body;

    /**
     * @ORM\Column(type="integer")
     */
    private int $number_chapter;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="chapters")
     * @ORM\JoinColumn(nullable=false)
     */
    private Post $postId;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private int $likeCounter=0;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="chapterId")
     *
     */
    private Collection $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id=$id;

        return $this;
    }

    public function __toString(): string
    {
        return "HELLO";
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getNumberChapter(): ?int
    {
        return $this->number_chapter;
    }

    public function setNumberChapter(int $number_chapter): self
    {
        $this->number_chapter = $number_chapter;

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

    public function getLikeCounter(): ?int
    {
        return $this->likeCounter;
    }

    public function setLikeCounter(int $likeCounter): self
    {
        $this->likeCounter = $likeCounter;

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setChapterId($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getChapterId() === $this) {
                $like->setChapterId(null);
            }
        }

        return $this;
    }
}
