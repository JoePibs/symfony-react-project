<?php

namespace App\Repository;

use App\DTO\CategoryWithCount;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Undocumented function
     *
     * @return CategoryWithCountDTO[]
     */
    public function findAllWithCount(): array
    {
        return $this->createQueryBuilder('c')
            ->select('NEW App\DTO\CategoryWithCount(c.id, c.name, COUNT(r.id))')
            ->leftJoin('c.recipes', 'r')
            ->groupBy('c.id')
            ->getQuery()
            ->getResult();
    }
}
