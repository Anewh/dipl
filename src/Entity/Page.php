<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use phpDocumentor\Reflection\Types\Nullable;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pageShow', 'projectShow'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['pageShow', 'projectShow'])]
    
    private ?string $file = null;

    #[ORM\ManyToOne(inversedBy: 'pages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['pageShow'])]
    private ?Project $project = null;

    // #[ORM\Column(length: 255, nullable: true)]
    // #[Groups(['pageShow', 'projectShow'])]
    // private ?string $relation = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'pages')]
    #[Groups(['pageShow', 'projectShow'])]
    private ?self $parent = null;


    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    //#[Groups(['pageShow', 'projectShow'])]
    private Collection $pages;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['pageShow', 'projectShow'])]
    private ?string $header = null;


    public function __construct()
    {
        $this->pages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    // public function getRelation(): ?string
    // {
    //     return $this->relation;
    // }

    // public function setRelation(string $relation): self
    // {
    //     $this->relation = $relation;

    //     return $this;
    // }

    public function getParent(): ?self
    {
        //return $this->parent?$this->parent->getId(): -1;
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(self $page): self
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
            $page->setParent($this);
        }

        return $this;
    }

    // public function removePage(self $page): self
    // {
    //     if ($this->pages->removeElement($page)) {
    //         // set the owning side to null (unless already changed)
    //         if ($page->getParent() === $this) {
    //             $page->setParent(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setHeader(string $header): self
    {
        $this->header = $header;

        return $this;
    }
    
    public function __toString(): string
    {
        return $this->getHeader();
    }
}
