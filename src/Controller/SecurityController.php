<?php

namespace App\Controller;

use App\Entity\CollectionCarte;
use App\Entity\ListeDeSouhaits;
use App\Entity\Deck;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
//    se connecter au site
    #[Route(path: '/login', name: 'login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, UserRepository $userRepository): Response
    {
//        récupère le formulaire
        $form = $this->createForm(RegistrationType::class, $this->getUser());
        $form->handleRequest($request);

//        si il parvient à trouver un user, il retourne la page compte du user
         if ($this->getUser()) {
             return $this->redirectToRoute('compte');
         }

//         on récupère no éléments pour afficher la page login
        $users = $userRepository->findAll();
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

//        retourne la page s'inscrire
        return $this->render('default/login.html.twig', ['registerForm'=> $form->createView(), 'last_username' => $lastUsername, 'error' => $error, 'users'=> $users]);
    }

//    s'enregistrer au site
    #[Route(path: '/register', name: 'register')]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response {
//        récupère nos données
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $verify = $userRepository->findOneBy(['email' => $user->getEmail()]);

//        si lors de la vérification, il ne parvient pas à trouver le user lié au mail, il vérifie si le formulaire est envoyé et valide
//        puis il crée un nouveau user
        if ($verify == null) {
            if ($form->isSubmitted() && $form->isValid()) {

                $collectionCarte = new CollectionCarte();
                $listeDeSouhaits = new listeDeSouhaits();
                $deck = new Deck();

                $user->setPlainPassword($user->getPlainPassword());
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPlainPassword()
                );
                $user->setPassword($hashedPassword);
                $user->setCompte(true);
                $user->addCollectionCarte($collectionCarte);
                $user->addListeDeSouhaits($listeDeSouhaits);
                $user->addDeck($deck);
//                si l'adresse mail correspond à l'une de ces adresses, le user devient admin
                if ($user->getEmail() == "yaelbrugmans1998@gmail.com" or $user->getEmail() == "admin@admin.com")
                    $user->setRoles(['ROLE_ADMIN']);
                else
                    $user->setRoles(['ROLE_USER']);

                $em->persist($user);
                $em->persist($collectionCarte);
                $em->persist($listeDeSouhaits);
                $em->persist($deck);
                $em->flush();

//              retourne la page "se connecter"
                return $this->redirectToRoute('login');
            }
        }

//        retourne la page s'inscrire
        return $this->render('default/register.html.twig', ['registerForm'=> $form->createView()]);
    }

//    se déconnecter
    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException();
    }

//    affiche le tableau de bord adminnistrateur
    #[Route(path: '/admin/adminDashboard', name: 'adminDashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminDashboard(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $users = $userRepository->findAll();

        return $this->render('admin_dashboard/index.html.twig', ['user'=> $user, 'users' => $users]);
    }

}
