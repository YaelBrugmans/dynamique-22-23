<?php

namespace App\Repository;

use App\Entity\ListeDeSouhaits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListeDeSouhaits>
 *
 * @method ListeDeSouhaits|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListeDeSouhaits|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListeDeSouhaits[]    findAll()
 * @method ListeDeSouhaits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListeDeSouhaitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeDeSouhaits::class);
    }

    public function save(ListeDeSouhaits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ListeDeSouhaits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
