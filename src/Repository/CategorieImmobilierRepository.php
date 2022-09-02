<?php

namespace App\Repository;

use App\Entity\CategorieImmobilier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieImmobilier|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieImmobilier|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieImmobilier[]    findAll()
 * @method CategorieImmobilier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieImmobilierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieImmobilier::class);
    }

    // /**
    //  * @return CategorieImmobilier[] Returns an array of CategorieImmobilier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieImmobilier
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
