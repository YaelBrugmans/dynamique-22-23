<?php


namespace App\Controller;

use App\Entity\Carte;
use App\Entity\Commentaire;
use App\Form\CarteType;
use App\Form\CommentaireType;
use App\Repository\CarteRepository;
use App\Repository\CommentairesRepository;
use App\Upload\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CarteController extends AbstractController
{
//  affiche un rendu d'une carte
    #[Route('/detailDeCarte/{id}', name: 'detailDeCarte')]
    public function detailDeCarte(Request $request, CarteRepository $carteRepository, EntityManagerInterface $entityManager, int $id): Response
    {
//        recherche nos éléments
        $carte = $carteRepository->find($id);
        $commentaire = new Commentaire();

//        récupère le formulaire de commentaire
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

//        si le formulaire est submit et valide, on persist le commentaire dans la db
        if ($form->isSubmitted() and $form->isValid()) {
                $commentaire->setContenu($commentaire->getContenu());
                $commentaire->setDateCommentaire(new \DateTime());
                $commentaire->setCarte($carte);
                $commentaire->setUser($this->getUser());
                $entityManager->persist($commentaire);
                $entityManager->flush();
        }
//        réaffiche la page "détail de carte"
        return $this->render('default/detailDeCarte.html.twig', ['carte' => $carte, 'commentaireForm'=> $form->createView()]);
    }

//    efface un commentaire, seul l'utilisateur d'origine et les administrateurs peuvent supprimer les commentaires de ce premier
    #[Route('/deleteCommentaire/{id}', name: 'deleteCommentaire')]
    public function deleteCommentaire(CommentairesRepository $commentairesRepository, EntityManagerInterface $entityManager, int $id): Response
    {
//        recherche nos éléments
        $commentaire = $commentairesRepository->find($id);
        $roleUser = $this->getUser()->getRoles();

//      si il y a un commentaire et que l'utilisateur est soit l'auteur du commentaire soit un admin, on supprime le commentaire et revient à la page de détails
        if ($commentaire and ($this->getUser() === $commentaire->getUser() or $roleUser[0] == 'ROLE_ADMIN')) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
            return $this->redirectToRoute('detailDeCarte', ['id'=> $commentaire->getCarte()->getId(), 'message' => 'Commentaire supprimé']);
        }
//      en cas d'échec du if, on se dirige vers index et on envoi un message invalide
        return $this->redirectToRoute('index', ['message' => 'Commentaire invalide pour la suppression']);
    }

//    permet de créer une carte, fonction accessible que par les administrateurs
    /**
     * @throws ErrorException
     */
    #[Route('/carteForm/create', name: 'carteFormCreate')]
    #[IsGranted('ROLE_ADMIN')]
    public function createCarte(Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response {
//        recherche nos éléments
        $carte = new Carte();
        $form = $this->createForm(CarteType::class, $carte);
        $form->handleRequest($request);
        $roleUser = $this->getUser()->getRoles();

//        si le formulaire est valide et que le user est un admin, on crée la carte et on affiche sa page détails
        if ($form->isSubmitted() and $form->isValid() and $roleUser[0] == 'ROLE_ADMIN') {
//            upload de fichiers
//            catégorie de l'image à uploader
            $folder = 'cartes';
//            upload de l'image via le formulaire et sa catégorie
            $fileUploader->uploadFilesFromForm($form, $folder);
            $folder = 'expansions';
            $fileUploader->uploadFilesFromForm($form, $folder);
            $entityManager->persist($form->getData());

            $carte->setImage($carte->getImage());
            $carte->setNom($carte->getNom());
            $carte->setDescription($carte->getDescription());
            $carte->setArtiste($carte->getArtiste());
            $carte->setCouleur($carte->getCouleur());
            $carte->setCoutCarte($carte->getCoutCarte());
            $carte->setExpansion($carte->getExpansion());
            $carte->setPrix($carte->getPrix());
            $carte->setAtkDef($carte->getAtkDef());

            $entityManager->persist($carte);
            $entityManager->flush();
            return $this->redirectToRoute('detailDeCarte', ['id'=> $carte->getId()]);
        }
//        si le if échoue, on revient au formulaire avec un message d'erreur
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('default/creationDeCarte.html.twig', ['carteForm' => $form->createView(), 'error' => $error]);
    }

//    met à jour une carte, fonction accessible que par les administrateurs
    #[Route('/carte/update/{id}', name: 'carteFormUpdate')]
    #[IsGranted('ROLE_ADMIN')]
    public function updateCarte(CarteRepository $carteRepository, Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils, int $id): Response {
        //        recherche nos éléments
        $carte = $carteRepository->find($id);
        $form = $this->createForm(CarteType::class, $carte);
        $form->handleRequest($request);
        $roleUser = $this->getUser()->getRoles();

//        si le formulaire est valide et que le user est admin, on modifie la carte et on affiche sa page de détail
        if ($form->isSubmitted() and $form->isValid() and $roleUser[0] == 'ROLE_ADMIN') {
//            modifie les fichiers images
//            catégorie de l'image
            $folder = 'cartes';
            //            upload de l'image via le formulaire et sa catégorie
            $fileUploader->uploadFilesFromForm($form, $folder);
            $folder = 'expansions';
            $fileUploader->uploadFilesFromForm($form, $folder);

            $entityManager->persist($form->getData());
            $entityManager->persist($carte);
            $entityManager->flush();
            return $this->redirectToRoute('detailDeCarte', ['id'=> $carte->getId()]);
        }
        //        si le if échoue, on revient au formulaire avec un message d'erreur
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('default/creationDeCarte.html.twig', ['id'=> $carte->getId(), 'carteForm' => $form->createView(), 'error' => $error]);
    }

//    efface une carte, fonction accessible que par les administrateurs
    #[Route('/carte/deleteCarte/{id}', name: 'deleteCarte')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteCarte(CarteRepository $carteRepository, EntityManagerInterface $entityManager, int $id): Response
    {
//        on récupère nos éléments
        $carte = $carteRepository->find($id);
        $roleUser = $this->getUser()->getRoles();

//        si la carte existe et que l'utilisateur est un admin, on supprime la carte
        if ($carte and $roleUser[0] == "ROLE_ADMIN"){
            $entityManager->remove($carte);
            $entityManager->flush();
        }
//        retourne à l'index
        return $this->redirectToRoute('index');
    }
}
