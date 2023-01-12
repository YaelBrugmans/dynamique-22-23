<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $compte = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    private ?string $plainPassword;
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CollectionCarte::class)]
    private ?Collection $collection_carte;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Deck::class)]
    private ?Collection $deck;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaire::class)]
    private ?Collection $commentaire;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ListeDeSouhaits::class)]
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    /**
     * @return bool|null
     */
    public function getCompte(): ?bool
    {
        return $this->compte;
    }

    /**
     * @param bool|null $compte
     */
    public function setCompte(?bool $compte): void
    {
        $this->compte = $compte;
    }

    /**
     * @return string|null
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @param string|null $pseudo
     */
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return Collection<int, CollectionCarte>
     */
    public function getCollectionCarte(): Collection
    {
        return $this->collection_carte;
    }

    public function setCollectionCarte(Collection $collection_carte): Collection
    {
        $this->collection_carte = $collection_carte;

        return $this->collection_carte;
    }

    public function addCollectionCarte(CollectionCarte $collection_carte): self
    {
        if (!$this->collection_carte->contains($collection_carte)) {
            $this->collection_carte->add($collection_carte);
            $collection_carte->setUser($this);
        }

        return $this;
    }

    public function emptyCollectionCarte(): self
    {
        $this->collection_carte->forAll(function($entity) {
            $this->collection_carte->remove($entity);
            return true;
        });
        return $this;
    }

    public function addCarteInCollection(CollectionCarte $collection_carte, Carte $carte): self
    {
        if (!$collection_carte->getCartes()->contains($carte)) {
            $collection_carte->addCarte($carte);
            $carte->addCollectionCarte($collection_carte);
        }
        return $this;
    }

    /**
     * @return Collection<int, Deck>
     */
    public function getDeck(): Collection
    {
        return $this->deck;
    }

    public function addDeck(Deck $decks): self
    {
        if (!$this->deck->contains($decks)) {
            $this->deck->add($decks);
            $decks->setUser($this);
        }

        return $this;
    }

    public function removeDeck(Deck $deck): self
    {
        if ($this->deck->removeElement($deck)) {
            if ($deck->getUser() === $this) {
                $deck->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ListeDeSouhaits>
     */
    public function getListeDeSouhaits(): Collection
    {
        return $this->liste_de_souhaits;
    }

    public function addListeDeSouhaits(ListeDeSouhaits $liste_de_souhaits): self
    {
        if (!$this->liste_de_souhaits->contains($liste_de_souhaits)) {
            $this->liste_de_souhaits->add($liste_de_souhaits);
            $liste_de_souhaits->setUser($this);
        }

        return $this;
    }

    public function removeListeDeSouhaits(ListeDeSouhaits $liste_de_souhaits): self
    {
        if ($this->liste_de_souhaits->removeElement($liste_de_souhaits)) {
            if ($liste_de_souhaits->getUser() === $this) {
                $liste_de_souhaits->setUser(null);
            }
        }

        return $this;
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
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaire->removeElement($commentaire)) {
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }
}
