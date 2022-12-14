<?php

namespace App\Repository;

use App\Entity\Immobilier;
use App\Entity\SearchImmobilier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method Immobilier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Immobilier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Immobilier[]    findAll()
 * @method Immobilier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImmobilierRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */ 
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Immobilier::class);

        $this->paginator = $paginator;
    }

    /**
     * @return Immobilier[]
     */
    public function findFeatured(): array
    {
        return $this->findVisibleQuery()
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Immobilier[] Returns an array of Immobilier objects
     */
    public function findVisibleByDateDesc(): array
    {
        return $this->findVisibleQuery()
            ->getQuery()
            ->getResult();
    }

    public function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->where('i.online = 1')
            ->orderBy('i.created', 'DESC');
    }

    /**
    * @return Immobilier[] Returns an array of Immobilier objects
    */
    public function findFuturedDesc()
    {
        return $this->createQueryBuilder('i')
            ->select('c', 'i')
            ->select('v', 'i')
            ->join('i.categorie', 'c')
            ->join('i.ville', 'v')
            ->setMaxResults(4)
            ->andWhere('i.online = 1')
            ->orderBy('i.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Immobilier[] Returns an array of Immobilier objects
    */
    public function findSimilaires($categorie, $immobilier)
    {
        return $this->createQueryBuilder('i')
            ->select('c', 'i')
            ->select('v', 'i')
            ->join('i.categorie', 'c')
            ->join('i.ville', 'v')
            ->setMaxResults(12)
            ->andWhere('i.online = 1')
            ->andWhere('c = :categorie')
            ->setParameter('categorie', $categorie)
            ->distinct($immobilier)
            ->orderBy('i.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Immobilier[] Returns an array of Immobilier objects
    */
    public function findByDateDesc()
    {
        return $this->createQueryBuilder('i')
            ->select('c', 'i')
            ->select('v', 'i')
            ->join('i.categorie', 'c')
            ->join('i.ville', 'v')
            ->andWhere('i.online = 1')
            ->orderBy('i.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Immobilier
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Recup??re les annonces en lien avec une recherche
     * @return PaginationInterface
     */
    public function findVisibleSearch(SearchImmobilier $search): PaginationInterface
    {
        $query = $this->getSearcheVisibleQuery($search)->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            12
        );

    }

    /**
     * Recup??re les annonces en lien avec une recherche
     * @return PaginationInterface
     */
    public function findAllSearch(SearchImmobilier $search): PaginationInterface
    {
        $query = $this->getAllSearchQuery($search)->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            20
        );

    }

    /**
     * Recup??re le prix min et max corespondant ?? une recherche
     * @return integer[]
     */
    private function findMinMaxPrice(SearchImmobilier $search): array
    {
        $result = $this->getSearcheVisibleQuery($search, true)
            ->select('MIN(i.tarif) as min', 'MAX(i.tarif) as max')
            ->getQuery()
            ->getScalarResult()
        ;

        return [(int)$result[0]['min'], (int)$result[1]['max']];
    }

    /**
     * //@return QueryBuilder
     */
    private function getSearcheVisibleQuery(SearchImmobilier $search, $ignoreprice = false)//: QueryBuilder
    {
        $query = $this->createQueryBuilder('i')
            ->select('c', 'i')
            ->select('v', 'i')
            ->join('i.categorie', 'c')
            ->join('i.ville', 'v')
            ->orderBy('i.created', 'DESC')
            ->andWhere('i.online = 1');

        $this->getSearchFields($search, $query);

        return $query;
    }

    public function getAllSearchQuery(SearchImmobilier $search, $ignoreprice = false){

        $query = $this->createQueryBuilder('i')
            ->select('c', 'i')
            ->select('v', 'i')
            ->join('i.categorie', 'c')
            ->join('i.ville', 'v')
            ->orderBy('i.created', 'DESC');

        $this->getSearchFields($search, $query);

        return $query;

    }

    private function getSearchFields(SearchImmobilier $search, $query){

        if(!empty($search->q)){
            $query = $query
            ->andWhere('i.name LIKE :q')
            ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->type)) {
            $query = $query
            ->andWhere('i.type = :type')
            ->setParameter('type', $search->type);            
        }

        if (!empty($search->statut)) {
            $query = $query
            ->andWhere('i.statut = :statut')
            ->setParameter('statut', $search->statut);            
        }

        if (!empty($search->minTarif && $ignoreprice === false)) {
            $query = $query
            ->andWhere('i.tarif >= :minTarif')
            ->setParameter('minTarif', $search->minTarif);
        }

        if (!empty($search->maxTarif && $ignoreprice === false)) {
            $query = $query
            ->andWhere('i.tarif <= :maxTarif')
            ->setParameter('maxTarif', $search->maxTarif);
        }

        if($search->getCategories()->count() > 0){
            $query = $query
            ->andWhere('c.id IN (:categorie)')
            ->setParameter('categorie', $search->categories);
        }

        if($search->getVilles()->count() > 0){
            $query = $query
            ->andWhere('v.id IN (:ville)')
            ->setParameter('ville', $search->villes);
        }

        if (!empty($search->online)) {
            $query = $query
            ->andWhere('i.online = 1');
        }

        return $search;
    }
}
