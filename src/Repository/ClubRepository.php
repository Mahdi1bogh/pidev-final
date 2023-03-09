<?php

namespace App\Repository;

use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Club>
 *
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }

    public function save(Club $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Club $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   
    public function recherche($value): array
    {
        $qb= $this->createQueryBuilder('c')
            ->andWhere('c.id LIKE :val')
            ->orWhere('c.name LIKE :val')
            ->orWhere(' c.location LIKE :val')
            ->setParameter('val', '%'.$value.'%');
          
         return $qb->getQuery()->getResult();
        
    }
   
       public function Trieparclub(): array
   {
       return $this->createQueryBuilder('c')
           ->orderBy('c.name', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }
   public function sortt($sort): array
   {
       return $this->createQueryBuilder('c')
           ->orderBy('c.' . $sort, 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Club
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
