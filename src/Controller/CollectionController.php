<?php


namespace App\Controller;

use App\Repository\CarteRepository;
use App\Repository\CollectionCarteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{

//    affiche l'ensemble des cartes de la collection
    #[Route('/collectionCarte', name: 'collectionCarte')]
    public function collectionCarte(UserRepository $userRepository): Response
    {
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);
        $collectionCarte = $user->getCollectionCarte()->getValues();
        return $this->render('profile/collection.html.twig', ['collectionCarte' => $collectionCarte]);
    }

//  ajoute une carte à la collection de l'utilisateur
    #[Route('/addCollectionCarte/{id}', name: 'addCollectionCarte')]
    public function addCollectionCarte(CarteRepository $carteRepository, EntityManagerInterface $entityManager, CollectionCarteRepository $collectionCarteRepository, UserRepository $userRepository, int $id): Response
    {
//        récupère nos éléments
        $carte = $carteRepository->find($id);
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);
        $collectionCarte = [];

//        si l'utilisateur existe, on ajoute la carte à sa collection
        if ($user) {
//            récupère la collection
            foreach ($user->getCollectionCarte()->getValues() as $coll){
                $collectionId = $coll->getId();
                $collectionCarte = $collectionCarteRepository->find($collectionId);
            }
            $collectionCarte->addCarte($carte);
            $entityManager->persist($collectionCarte);
            $entityManager->flush();
        }
//        retourne la page collection de l'utilisateur
        return $this->render('profile/collection.html.twig', ['collectionCarte' => $user->getCollectionCarte()->getValues(), 'user' => $user]);
    }

//  retire une carte de la collection
    #[Route('/removeCarteCollection/{id}', name: 'removeCarteCollection')]
    public function removeCarteCollection(CarteRepository $carteRepository, EntityManagerInterface $entityManager, CollectionCarteRepository $collectionCarteRepository, UserRepository $userRepository, int $id): Response
    {
//        réécupère nos éléments
        $carte = $carteRepository->find($id);
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);
        $collectionCarte = $user->getCollectionCarte();

//        si le user existe, on récupère la collection et on y supprime la carte
        if ($user) {
//            récupère la collection
            foreach ($user->getCollectionCarte()->getValues() as $coll){
                $collectionId = $coll->getId();
                $collectionCarte = $collectionCarteRepository->find($collectionId);
            }
            $collectionCarte->removeCarte($carte);
            $entityManager->persist($user);
            $entityManager->flush();
        }
//        retourne la page de connexion de l'utilisateur
        return $this->render('profile/collection.html.twig', ['collectionCarte' => $user->getCollectionCarte()->getValues(), 'user' => $user]);
    }
}
