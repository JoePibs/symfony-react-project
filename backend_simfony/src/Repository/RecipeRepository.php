<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
    * @return Recipe[] an array of Recipe objects
    */

    public function findRecipeWithLowDuration(int $duration): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->andWhere('r.duration < :duration')
            ->setParameter('duration', $duration)
            ->leftJoin('r.category', 'c')
            ->setMaxResults((20))
            ->orderBy('r.duration', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->leftJoin('r.category', 'c')
            ->setMaxResults((1000))
            ->orderBy('r.duration', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllByCategory($slug): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->leftJoin('r.category', 'c')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->setMaxResults(1000)
            ->orderBy('r.duration', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
