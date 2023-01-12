<?php

namespace App\Repository;

use App\Entity\Carte;
use App\Search\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carte>
 *
 * @method Carte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carte[]    findAll()
 * @method Carte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carte::class);
    }

    public function save(Carte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Carte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    fonction permettant de trouver des cartes par une entité search
    /**
     * @return Carte[]
     */
    public function findBySearch(Search $search): array
    {
        $db = $this->createQueryBuilder('a');
//        titre de la carte
        if ($search->getSearchText()) {
            $db->where('a.nom like :searchText')
                ->setParameter('searchText', '%' . $search->getSearchText(). '%');
        }
        //        prix maximal de la carte
        if ($search->getMaxPrice()) {
            $db->andWhere('a.prix<=:value')
                ->setParameter('value', $search->getMaxPrice());
        }
        //        couleur de la carte
        if ($search->getCouleur()) {
            $db->andWhere('a.couleur like :col')
                ->setParameter('col', '%' . $search->getCouleur() . '%');
        }
        return $db->getQuery()->getResult();
    }

//    fonction permettant de trouver des cartes par leur expansion
    /**
     * @return Carte[]
     */
    public function findByExpansion(string $expansion): array
    {
        $db = $this->createQueryBuilder('a');
        if ($expansion) {
            $db->where('a.expansion like :Expansion')
                ->setParameter('Expansion', '%' . $expansion . '%');
        }
        return $db->getQuery()->getResult();
    }
}
