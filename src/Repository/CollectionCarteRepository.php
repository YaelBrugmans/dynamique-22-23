<?php

namespace App\Repository;

use App\Entity\CollectionCarte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CollectionCarte>
 *
 * @method CollectionCarte|null find($id, $lockMode = null, $lockVersion = null)
 * @method CollectionCarte|null findOneBy(array $criteria, array $orderBy = null)
 * @method CollectionCarte[]    findAll()
 * @method CollectionCarte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionCarteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CollectionCarte::class);
    }

    public function save(CollectionCarte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CollectionCarte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
