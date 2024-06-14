<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\BanWord;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[UniqueEntity('title')]
#[UniqueEntity('slug')]
#[Vich\Uploadable()]

class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, max: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^[\p{L}0-9\s_-]+$/u", message: 'Only letters, numbers, spaces, hyphens, and underscores are allowed')]
    #[BanWord()]
    #[Groups(['recipe'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['detail_recipe'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 10, max: 255)]
    #[Groups(['detail_recipe'])]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups(['recipe'])]
    private ?string $duration = null;

    #[ORM\Column(nullable: true)]
    private ?string $ingredients = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'recipes', cascade: ['persist'])]
    #[Groups(['detail_recipe'])]
    private ?Category $category = null;

    #[Vich\UploadableField(mapping:"recettes", fileNameProperty:"thumbnail")]
    #[Assert\Image()]
    private ?File $thumbnailFile = null;

    #[Groups(['detail_recipe'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?user $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(?string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(?File $thumbnailFile): static
    {
        $this->thumbnailFile = $thumbnailFile;
        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }
}
