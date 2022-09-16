<?php

namespace App\Repository;

use App\Entity\ImmobilierMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImmobilierMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImmobilierMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImmobilierMedia[]    findAll()
 * @method ImmobilierMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImmobilierMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImmobilierMedia::class);
    }

    /**
    * @return ImmobilierMedia[] Returns an array of ImmobilierMedia objects
    */
    public function findByDateDesc()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?ImmobilierMedia
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
