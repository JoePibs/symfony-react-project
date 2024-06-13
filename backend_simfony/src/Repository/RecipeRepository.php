<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
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

    public function paginateRecipes(int $page): PaginationInterface
    {
        /*
        $offset = ($page - 1) * $limit;
        return new Paginator($this
            ->createQueryBuilder('r')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->setHint(Paginator::HINT_ENABLE_DISTINCT, false)
        );
        */
        return $this->paginator->paginate(
            $this->createQueryBuilder('r')->leftJoin('r.category', 'c')->select('r', 'c'),
            $page,
            3,
            [ 'distinct' => false,
            'shortFieldAllowList' => ['r.id']
            ]
        );
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
