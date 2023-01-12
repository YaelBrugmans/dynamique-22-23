<?php

namespace App\Entity;

use App\Repository\CollectionCarteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollectionCarteRepository::class)]
class CollectionCarte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Carte::class, mappedBy: 'collection_carte')]
    private Collection $carte;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'collection_carte')]
    private ?User $user;

    public function __construct()
    {
        $this->carte = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $users): self
    {
        $this->user = $users;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCartes(): Collection
    {
        return $this->carte;
    }

    public function addCarte(Carte $carte): self
    {
        if (!$this->carte->contains($carte)) {
            $this->carte->add($carte);
            $carte->addCollectionCarte($this);
        }

        return $this;
    }

    public function removeCarte(Carte $carte): self
    {
        if ($this->carte->removeElement($carte)) {
            $carte->removeCollectionAllCarte();
        }

        return $this;
    }
}
