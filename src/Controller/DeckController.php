<?php


namespace App\Controller;

use App\Entity\Deck;
use App\Repository\CarteRepository;
use App\Repository\DeckRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeckController extends AbstractController
{
//  récupère un deck
    #[Route('/deck/{id}', name: 'deck')]
    public function deck(UserRepository $userRepository, DeckRepository $deckRepository, int $id): Response
    {
        $deck = $deckRepository->find($id);
        return $this->render('profile/deck.html.twig', ['deck' => $deck]);
    }

//    récupère tous les decks de l'utilisateur
    #[Route('/decks', name: 'decks')]
    public function decks(UserRepository $userRepository): Response
    {
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);
        $decks = $user->getDeck()->getValues();
        return $this->render('profile/decks.html.twig', ['decks' => $decks]);
    }

//    ajoute un deck à l'ensemble des decks de l'utilisateur
    #[Route('/addDeck', name: 'addDeck')]
    public function addDeck(EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
//        récupère nos éléments
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);
        $newDecks = new Deck();

//        si le user existe, on ajoute un deck
        if ($user) {
            $user->addDeck($newDecks);
            $entityManager->persist($newDecks);
            $entityManager->persist($user);
            $entityManager->flush();
        }
//        retourne la page affichant l'ensemble des decks
        return $this->render('profile/decks.html.twig', ['decks' => $user->getDeck()->getValues(), 'user' => $user]);
    }

//    retire un deck
    #[Route('/removeDeck/{id}', name: 'removeDeck')]
    public function removeDeck(EntityManagerInterface $entityManager, DeckRepository $deckRepository, UserRepository $userRepository, int $id): Response
    {
//        récupère nos éléments
        $decks = $deckRepository->find($id);
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);

//        si l'utilisateur existe, on supprime un deck
        if ($user) {
            $user->removeDeck($decks);
            $entityManager->persist($user);
            $entityManager->flush();
        }
//        retourne la page affichant l'ensemble des decks
        return $this->render('profile/decks.html.twig', ['decks' => $user->getDeck()->getValues(), 'user' => $user]);
    }


//    #[Route('/prepareDeck', name: 'prepareDeck')]
//    public function prepareDeck(UserRepository $userRepository, CarteRepository $carteRepository, int $id): Response
//    {
//        $carte = $carteRepository->find($id);
//        $thisUser = $this->getUser()->getUserIdentifier();
//        $user = $userRepository->findOneBy(['email' => $thisUser]);
//        $decks = $user->getDeck()->getValues();
//
//        return $this->render('profile/decks.html.twig', ['decks' => $decks]);
//    }

//  ajoute une carte à UN deck
    #[Route('/addDeckCarte/{id}', name: 'addDeckCarte')]
    public function addDeckCarte(CarteRepository $carteRepository, EntityManagerInterface $entityManager, DeckRepository $deckRepository, UserRepository $userRepository, int $id): Response
    {
//        récupère nos éléments
        $carte = $carteRepository->find($id);
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);
        $deck = [];

//        si le user existe, on ajoute la carte au deck correspondant
        if ($user) {
            foreach ($user->getDeck()->getValues() as $de){
                $deckId = $de->getId();
                $deck = $deckRepository->find($deckId);
            }
            $deck->addCarte($carte);
            $entityManager->persist($deck);
            $entityManager->flush();
        }
//        retourne la page du deck dans lequel la carte a été placée
        return $this->render('profile/deck.html.twig', ['deck' => $user->getDeck()->getValues(), 'user' => $user]);
    }

//    retire une carte d'UN deck
    #[Route('/removeDeckCarte/{id}', name: 'removeDeckCarte')]
    public function removeDeckCarte(CarteRepository $carteRepository, EntityManagerInterface $entityManager, DeckRepository $deckRepository, UserRepository $userRepository, int $id): Response
    {
//        récupère nos données
        $carte = $carteRepository->find($id);
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);
        $deck = $user->getDeck();

//        si l'utilisateur existe, on supprime la carte du deck
        if ($user) {
            foreach ($user->getDeck()->getValues() as $de){
                $deckId = $de->getId();
                $deck = $deckRepository->find($deckId);
            }
            $deck->removeCarte($carte);
            $entityManager->persist($user);
            $entityManager->flush();
        }

//        retourne la page du deck s'étant fait déposédé de la carte
        return $this->render('profile/deck.html.twig', ['deck' => $user->getDeck()->getValues(), 'user' => $user]);
    }
}
