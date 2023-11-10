<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\Equipement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Equipement>
 *
 * @method Equipement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipement[]    findAll()
 * @method Equipement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipementRepository extends ServiceEntityRepository
{
    const ITEMS_PER_PAGE = 4;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipement::class);
    }

    public function add(Equipement $entity, bool $flush = false)
    {
        $this->getEntityManager()->persist($entity);

        if($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Equipement $entity, bool $flush = false)
    {
        $this->getEntityManager()->remove($entity);

        if($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Equipement[] Returns an array of Equipement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Equipement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function softDeleteById(string $id)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        $queryBuilder
            ->update('App\Entity\Equipement', 'e')
            ->set('e.deleted', true)
            ->where('e.id = :entityId')
            ->setParameter('entityId', $id)
            ->getQuery()
            ->execute();
    }

//    public function findAllWithPagination($page, $limit)
//    {
//        $qb = $this->createQueryBuilder('e')
//            ->setFirstResult(($page - 1) * $limit)
//            ->setMaxResults($limit);
//        return $qb->getQuery()->getResult();
//    }

    public function findAllWithPagination(Request $request): Paginator
    {
        $page = $request->query->get('page', 1);
        $firstResult = ($page -1) * self::ITEMS_PER_PAGE;

        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder
            ->where('e.deleted = FALSE');

        if($request->get('id')){
            $queryBuilder
                ->andWhere('e.id = :id')
                ->setParameter('id', $request->get('id'));
        }
        if($request->get('name')){
            $queryBuilder
                ->andWhere('e.name LIKE :name')
                ->setParameter('name', '%'.$request->get('name').'%');
        }
        if($request->get('category')){
            $queryBuilder
                ->andWhere('e.category = :category')
                ->setParameter('category', '%'.$request->get('category').'%');
        }
        if($request->get('number')){
            $queryBuilder
                ->andWhere('e.number = :number')
                ->setParameter('number', $request->get('number'));
        }
        if($request->get('order')??['id']){
            $queryBuilder->orderBy('e.id', $request->get('order')['id']);
        }

        $query = $queryBuilder->getQuery()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE);

        $doctrinePaginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        return new Paginator($doctrinePaginator);
    }
}
