<?php


namespace App\Controller;

use App\Repository\CarteRepository;
use App\Repository\ListeDeSouhaitsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeDeSouhaitsController extends AbstractController
{
//    affiche la liste de souhaits
    #[Route('/listeDeSouhaits', name: 'listeDeSouhaits')]
    public function listeDeSouhaits(UserRepository $userRepository): Response
    {
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);
        $listeDeSouhaits = $user->getListeDeSouhaits()->getValues();

        return $this->render('profile/listeDeSouhaits.html.twig', ['listeDeSouhaits' => $listeDeSouhaits]);
    }

//    ajoute une nouvelle liste de souhaits
    #[Route('/addListeDeSouhaits/{id}', name: 'addListeDeSouhaits')]
    public function addListeDeSouhaits(CarteRepository $carteRepository, EntityManagerInterface $entityManager, ListeDeSouhaitsRepository $listeDeSouhaitsRepository, UserRepository $userRepository, int $id): Response
    {
//        récupère nos éléments
        $carte = $carteRepository->find($id);
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);
        $listeDeSouhaits = [];

//        on récupère la liste du user et on y ajoute la carte
        foreach ($user->getListeDeSouhaits()->getValues() as $coll){
            $listeDeSouhaitsId = $coll->getId();
            $listeDeSouhaits = $listeDeSouhaitsRepository->find($listeDeSouhaitsId);
        }
        $listeDeSouhaits->addCarte($carte);

        $entityManager->persist($user);
        $entityManager->persist($listeDeSouhaits);
        $entityManager->flush();

//        retourne la page de la "liste de souhaits"
        return $this->render('profile/listeDeSouhaits.html.twig', ['listeDeSouhaits' => $user->getListeDeSouhaits()->getValues(), 'user' => $user]);
    }

//    supprime la liste de souhaits de l'utilisateur
    #[Route('/removeListeDeSouhaits/{id}', name: 'removeListeDeSouhaits')]
    public function removeListeDeSouhaits(CarteRepository $carteRepository, EntityManagerInterface $entityManager, UserRepository $userRepository, int $id): Response
    {
//        récupère nos éléments
        $carte = $carteRepository->find($id);
        $thisUser = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $thisUser]);
        $listeDeSouhaits = $user->getListeDeSouhaits();

//        si il y a un utilisateur, on retire la carte de la liste
        if ($user) {
            $listeDeSouhaits->remove($carte);
            $entityManager->persist($user);
            $entityManager->flush();
        }

//        retourne la page "liste de souhaits
        return $this->render('profile/listeDeSouhaits.html.twig', ['ListeDeSouhaits' => $user->getListeDeSouhaits()->getValues(), 'user' => $user]);
    }

}
