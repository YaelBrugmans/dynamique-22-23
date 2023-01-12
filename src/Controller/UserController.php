<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\CollectionCarteRepository;
use App\Repository\DeckRepository;
use App\Repository\ListeDeSouhaitsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
//    la page affichant les données utilisateur, son compte
    #[Route('compte', name: 'compte')]
    public function compte(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request, UserPasswordHasherInterface $passwordHasher, CollectionCarteRepository $collectionCarteRepository, ListeDeSouhaitsRepository $listeDeSouhaitsRepository, DeckRepository $deckRepository): Response
    {
//        si le post contient updateUser, alors il modifiera les données utilisateur
        if (isset($_POST['updateUser'])){
            $userIdentifier = $this->getUser()->getUserIdentifier();
            $user = $userRepository->findOneBy(['email' => $userIdentifier]);
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            $user->setPlainPassword($user->getPlainPassword());
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPseudo($user->getPseudo());
            $user->setPassword($hashedPassword);
            $user->setEmail($user->getEmail());

            $message = 'Vos données ont été modifiées avec succès !';

            $entityManager->persist($user);
            $entityManager->flush();
        }
//        si le post contient deleteUser, alors il supprimera l'utilisateur
        elseif (isset($_POST['deleteUser'])){
            $userIdentifier = $this->getUser()->getUserIdentifier();
            $user = $userRepository->findOneBy(['email' => $userIdentifier]);
//            si le compte n'est pas admin, il supprime le user et les entitées lui appartenant
            if ($user->getRoles() != 'ROLE_ADMIN'){
                foreach ($user->getCollectionCarte()->getValues() as $coll){
                    $collectionId = $coll->getId();
                    $collectionCarte = $collectionCarteRepository->find($collectionId);
                    $entityManager->remove($collectionCarte);
                }
                foreach ($user->getListeDeSouhaits()->getValues() as $coll){
                    $listeId = $coll->getId();
                    $liste = $listeDeSouhaitsRepository->find($listeId);
                    $entityManager->remove($liste);
                }
                foreach ($user->getDeck()->getValues() as $coll){
                    $deckId = $coll->getId();
                    $deck = $deckRepository->find($deckId);
                    $entityManager->remove($deck);
                }

                $entityManager->remove($user);
                $request->getSession()->invalidate();
//                la session redevient null, pour aucun utilisateur connecté
                $this->container->get('security.token_storage')->setToken(null);
                $entityManager->flush();
            }
//            retourne la page index
            return $this->redirect('index');
        }
//        si aucun des cas ci-dessous n'est vrai, affiche simplement la page compte
        else{
            $userIdentifier = $this->getUser()->getUserIdentifier();
            $user = $userRepository->findOneBy(['email' => $userIdentifier]);
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
            $message = false;
        }
//        retourne la page compte
        return $this->render('profile/compte.html.twig', ['userForm' => $form->createView(), 'message' => $message]);
    }

//    supprime un user ciblé, fonction accesible uniquement par un administrateur
    #[Route('deleteTargetUser/{id}', name: 'deleteTargetUser')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteTargetUser(EntityManagerInterface $entityManager, UserRepository $userRepository, CollectionCarteRepository $collectionCarteRepository, ListeDeSouhaitsRepository $listeDeSouhaitsRepository, DeckRepository $deckRepository, int $id): Response
    {
        $user = $userRepository->find($id);
        $roleUser = $user->getRoles();

//        si l'utilisateur ciblé est un admin, l'utilisateur ciblé sera supprimé
        if ($roleUser[0] != 'ROLE_ADMIN') {
            foreach ($user->getCollectionCarte()->getValues() as $coll) {
                $collectionId = $coll->getId();
                $collectionCarte = $collectionCarteRepository->find($collectionId);
                $entityManager->remove($collectionCarte);
            }
            foreach ($user->getListeDeSouhaits()->getValues() as $coll) {
                $listeId = $coll->getId();
                $liste = $listeDeSouhaitsRepository->find($listeId);
                $entityManager->remove($liste);
            }
            foreach ($user->getDeck()->getValues() as $coll) {
                $deckId = $coll->getId();
                $deck = $deckRepository->find($deckId);
                $entityManager->remove($deck);
            }
            $entityManager->remove($user);
            $entityManager->flush();
        }
//        retourne la page du tableau de bord admin
        return $this->redirect('app_admin_dashboard');
    }
}