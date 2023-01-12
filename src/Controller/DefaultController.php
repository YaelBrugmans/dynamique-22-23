<?php

namespace App\Controller;

use App\Repository\CarteRepository;
use App\Search\Search;
use App\Form\SearchType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
//    affiche la page par défaut, index
    #[Route('/', name: 'home')]
    public function home(CarteRepository $carteRepository, Request $request, PaginatorInterface $paginator): Response
    {
//        récupère toutes les cartes
        $donnees = $carteRepository->findAll();

//        applique une pagination sur ces dernières
        $cartes = $paginator->paginate(
            $donnees, // les données à paginer
            $request->query->getInt('page', 1), // numéro de la page en cours, 1 par défaut
            10 // nombre de résultats par page
        );
//        retourne la page index
        return $this->render('default/index.html.twig', ['cartes' => $cartes, 'donnees' => $donnees]);
    }

//    page index
    #[Route('/index', name: 'index')]
    public function index(CarteRepository $carteRepository, Request $request, PaginatorInterface $paginator): Response
    {
//        récupères nos cartes
        $donnees = $carteRepository->findAll();

        //        applique une pagination sur ces dernières
        $cartes = $paginator->paginate(
            $donnees, // les données à paginer
            $request->query->getInt('page', 1), // numéro de la page en cours, 1 par défaut
            10 // nombre de résultats par page
        );
//        retourne la page index
        return $this->render('default/index.html.twig', ['cartes' => $cartes, 'donnees' => $donnees]);
    }

//    recherche une carte via un formulaire de recherche
    #[Route('/search', name: 'search')]
    public function search(Request $request, CarteRepository $carteRepository, PaginatorInterface $paginator): Response
    {
//        récupère nos éléments
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $results = [];
        $submit = false;

//        si le formulaire est envoyé et valide, on récupère les données du formulaire et on recherche à travers le titre, le prix maximum et la couleur
        if ($form->isSubmitted() && $form->isValid()) {
//            titre
            if ($form->get('searchText')->getData() != null)
                $search->setSearchText($form->get('searchText')->getData());
            else
                $search->setSearchText('');
//            prix maximale
            if ($form->get('maxPrice')->getData() != null)
                $search->setMaxPrice($form->get('maxPrice')->getData());
            else
                $search->setMaxPrice(2147483647);
//            couleur
            if ($form->get('couleur')->getData() != null)
                $search->setCouleur($form->get('couleur')->getData());
            else
                $search->setCouleur('');
            $results = $carteRepository->findBySearch($form->getData());
            $submit = true;
        }

//        applique une pagination sur nos résultats
        $pages = $paginator->paginate(
            $results, // les doonnées à paginer
            $request->query->getInt('page', 1), // la page en cours, ici 1
            10 // nombre de résultats par page
        );

//        retourne la page du moteur de recherche
        return $this->render('default/search.html.twig', ['searchForm' => $form->createView(), 'results' => $results, 'submit' => $submit, 'pages' => $pages]);
    }

//    recherche de cartes, spécifiquement via leur extension
    #[Route('/search/{expansionName}', name: 'searchExpansion')]
    public function searchExpansion(Request $request, CarteRepository $carteRepository, PaginatorInterface $paginator, string $expansionName): Response
    {
//        récupère nos données
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $results = [];
        $submit = false;

//        si un nom d'expansion a été envoyé, on recherche les cartes correspondantes
        if ($expansionName) {
            $expansionName = strtolower($expansionName);
            $results = $carteRepository->findByExpansion($expansionName);
            $submit = true;
        }

//        si le formulaire est envoyé et valide, on recherche la carte
        if ($form->isSubmitted() && $form->isValid()) {
//            titre
            if ($form->get('searchText')->getData() != null)
                $search->setSearchText($form->get('searchText')->getData());
            else
                $search->setSearchText('');
//            prix maximale
            if ($form->get('maxPrice')->getData() != null)
                $search->setMaxPrice($form->get('maxPrice')->getData());
            else
                $search->setMaxPrice(2147483647);
//            couleur
            if ($form->get('couleur')->getData() != null)
                $search->setCouleur($form->get('couleur')->getData());
            else
                $search->setCouleur('');
            $submit = true;
            $results = $carteRepository->findBySearch($search);
        }

//        applique une pagination sur notre recherche
        $pages = $paginator->paginate(
            $results, // les données à paginer
            $request->query->getInt('page', 1), // numéro de la page en cours, ici 1
            10 // nombre de résultats par page
        );
//        retourne la page de recherche
        return $this->render('default/search.html.twig', ['searchForm' => $form->createView(), 'results' => $results, 'submit' => $submit, 'pages' => $pages]);
    }

//    affiche la page statique "A propos de nous"
    #[Route('/aProposDeNous', name: 'aProposDeNous')]
    public function aProposDeNous(CarteRepository $carteRepository): Response
    {
        $cartes = $carteRepository->findAll();
        return $this->render('default/aProposDeNous.html.twig', ['cartes' => $cartes]);
    }

//    affiche la page statique "comment jouer"
    #[Route('/commentJouer', name: 'commentJouer')]
    public function commentJouer(CarteRepository $carteRepository): Response
    {
        $cartes = $carteRepository->findAll();
        return $this->render('default/commentJouer.html.twig', ['cartes' => $cartes]);
    }

//    affiche la page statique glossaire
    #[Route('/glossaire', name: 'glossaire')]
    public function glossaire(): Response
    {
        return $this->render('default/glossaire.html.twig');
    }

}