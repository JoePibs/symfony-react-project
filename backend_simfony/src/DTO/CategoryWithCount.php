<?php 

namespace App\DTO;

class CategoryWithCount
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $count
    )
    {
        
    }
}