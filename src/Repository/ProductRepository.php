<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByCategoryAndPriceRange(?int $categoryId, ?float $minPrice, ?float $maxPrice)
{
    $qb = $this->createQueryBuilder('p')
        ->select('p');

    if ($categoryId) {
        $qb->andWhere('p.category = :categoryId')
            ->setParameter('categoryId', $categoryId);
    }

    if ($minPrice) {
        $qb->andWhere('p.price >= :minPrice')
            ->setParameter('minPrice', $minPrice);
    }

    if ($maxPrice) {
        $qb->andWhere('p.price <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice);
    }

    return $qb->getQuery()->getResult();
}
public function findByCategoryName($cat)
{
    
        $qb = $this->createQueryBuilder('p')
            ->where('p.categorie_id = :c')
            ->setParameter('c', $cat)
        ;
        return $qb->getQuery()->getResult();
    
}

public function findByPriceLessThan($price)
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.price < :price')
        ->setParameter('price', $price)
        ->getQuery()
        ->getResult();
}
public function findByPriceRange($minPrice, $maxPrice)
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.price >= :min_price')
        ->andWhere('p.price <= :max_price')
        ->setParameter('min_price', $minPrice)
        ->setParameter('max_price', $maxPrice)
        ->getQuery()
        ->getResult();
}
public function findByPriceGreaterThan($price)
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.price > :price')
        ->setParameter('price', $price)
        ->getQuery()
        ->getResult();
}
public function findBySearchQuery($query)
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.title LIKE :search')
        ->setParameter('search', '%'.$query.'%')
        ->getQuery()
        ->getResult();
}

public function getCategoryCounts(): array
{
    $qb = $this->createQueryBuilder('p')
        ->select('c.name as category_name, COUNT(p.id) as product_count')
        ->join('p.category', 'c')
        ->groupBy('c.name')
        ->getQuery();

    return $qb->getResult();
}
public function getCategorytotalCounts(): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('c.name as category_name')
            ->addSelect('COUNT(p.id) as product_count')
            ->addSelect('SUM(p.Qty) as total_quantity')
            ->join('p.category', 'c')
            ->groupBy('c.id');

        return $qb->getQuery()->getResult();
    }
public function getQuantityStats(): array
{
    $qb = $this->createQueryBuilder('p')
        ->select('COUNT(p.id) as product_count, SUM(p.Qty) as total_qty, AVG(p.Qty) as avg_qty')
        ->getQuery();

    return $qb->getSingleResult();
}

}
