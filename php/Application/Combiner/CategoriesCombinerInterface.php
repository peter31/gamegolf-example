<?php declare(strict_types=1);

namespace App\Application\Combiner;

interface CategoriesCombinerInterface
{
    public function execute(array $parentCategories, array $childrenCategories, ?int $childrenLimit = null): array;
}