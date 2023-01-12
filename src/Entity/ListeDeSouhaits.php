<?php

namespace App\Entity;

use App\Repository\ListeDeSouhaitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeDeSouhaitsRepository::class)]
class ListeDeSouhaits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Carte::class, mappedBy: 'liste_de_souhaits')]
    private Collection $carte;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'liste_de_souhaits')]
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
     * @return CollectionCarte<int, Carte>
     */
    public function getCarte(): Collection
    {
        return $this->carte;
    }

    public function addCarte(Carte $carte): self
    {
        if (!$this->carte->contains($carte)) {
            $this->carte->add($carte);
            $carte->addListeDeSouhaits($this);
        }
        return $this;
    }

    public function removeCarte(Carte $carte): self
    {
        if ($this->carte->removeElement($carte)) {
            $carte->removeListeDeSouhaits($this);
        }
        return $this;
    }
}
