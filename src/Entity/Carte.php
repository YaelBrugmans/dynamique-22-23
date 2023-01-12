<?php

namespace App\Entity;

use App\Repository\CarteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarteRepository::class)]
class Carte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $expansion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $couleur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coutCarte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $artiste = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $atkDef = null;

    #[ORM\ManyToMany(targetEntity: CollectionCarte::class, inversedBy: 'carte')]
    private ?Collection $collection_carte;

    #[ORM\ManyToMany(targetEntity: Deck::class, inversedBy: 'carte')]
    private ?Collection $deck;

    #[ORM\OneToMany(mappedBy: 'carte', targetEntity: Commentaire::class)]
    private ?Collection $commentaire;

    #[ORM\ManyToMany(targetEntity: ListeDeSouhaits::class, inversedBy: 'carte')]
    private ?Collection $liste_de_souhaits;

    public function __construct()
    {
        $this->collection_carte = new ArrayCollection();
        $this->deck = new ArrayCollection();
        $this->liste_de_souhaits = new ArrayCollection();
        $this->commentaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getExpansion(): ?string
    {
        return $this->expansion;
    }

    public function setExpansion(?string $expansion): self
    {
        $this->expansion = $expansion;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getCoutCarte(): ?string
    {
        return $this->coutCarte;
    }

    public function setCoutCarte(?string $coutCarte): self
    {
        $this->coutCarte = $coutCarte;

        return $this;
    }

    public function getArtiste(): ?string
    {
        return $this->artiste;
    }

    public function setArtiste(?string $artiste): self
    {
        $this->artiste = $artiste;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAtkDef(): ?string
    {
        return $this->atkDef;
    }

    public function setAtkDef(?string $atkDef): self
    {
        $this->atkDef = $atkDef;

        return $this;
    }

    /**
     * @return Collection<int, CollectionCarte>
     */
    public function getCollectionCarte(): Collection
    {
        return $this->collection_carte;
    }

    public function addCollectionCarte(CollectionCarte $collection_carte): self
    {
        if (!$this->collection_carte->contains($collection_carte)) {
            $this->collection_carte->add($collection_carte);
            $collection_carte->addCarte($this);
        }

        return $this;
    }

    public function removeCollectionCarte(CollectionCarte $collection_carte): self
    {
        if ($this->collection_carte->removeElement($collection_carte)) {
            if ($collection_carte->getCartes()->contains($this)) {
                $collection_carte->removeCarte($this);
            }
        }

        return $this;
    }

    public function removeCollectionAllCarte(): void
    {
        $this->collection_carte = new ArrayCollection();
    }

    /**
     * @return Collection<int, Deck>
     */
    public function getDeck(): Collection
    {
        return $this->deck;
    }

    public function addDeck(Deck $deck): self
    {
        if (!$this->deck->contains($deck)) {
            $this->deck->add($deck);
            $deck->addCarte($this);
        }
        return $this;
    }

    public function removeDeck(Deck $deck): self
    {
        if ($this->deck->removeElement($deck)) {
            if ($deck->getCarte()->contains($this)) {
                $deck->removeCarte($this);
            }
        }
        return $this;
    }

    public function removeDeckAll(): void
    {
        $this->deck = new ArrayCollection();
    }

    /**
     * @return Collection<int, ListeDeSouhaits>
     */
    public function getListeDesouhaits(): Collection
    {
        return $this->liste_de_souhaits;
    }

    public function addListeDeSouhaits(ListeDeSouhaits $liste_de_souhaits): self
    {
        if (!$this->liste_de_souhaits->contains($liste_de_souhaits)) {
            $this->liste_de_souhaits->add($liste_de_souhaits);
            $liste_de_souhaits->addCarte($this);
        }
        return $this;
    }

    public function removeListeDeSouhaits(ListeDeSouhaits $liste_de_souhaits): self
    {
        if ($this->liste_de_souhaits->removeElement($liste_de_souhaits)) {
            if ($liste_de_souhaits->getCarte()->contains($this)) {
                $liste_de_souhaits->removeCarte($this);
            }
        }
        return $this;
    }

    public function removeListeDeSouhaitsAll(): void
    {
        $this->liste_de_souhaits = new ArrayCollection();
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire->add($commentaire);
            $commentaire->setCarte($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaire->removeElement($commentaire)) {
            if ($commentaire->getCarte() === $this) {
                $commentaire->setCarte(null);
            }
        }

        return $this;
    }
}
