<?php declare(strict_types=1);

namespace App\Application\Combiner;

use App\Application\ResponseModel\CategoryResponseModel;

class CategoriesCombiner implements CategoriesCombinerInterface
{
    public function execute(array $parentCategories, array $childrenCategories, ?int $childrenLimit = null): array
    {
        $parentCategories = array_combine(
            array_map(
                function (CategoryResponseModel $category) {
                    return $category->getCategoryId();
                },
                $parentCategories
            ),
            $parentCategories
        );

        array_map(
            function (CategoryResponseModel $childCategory) use ($parentCategories, $childrenLimit) {
                if (array_key_exists($childCategory->getParentId(), $parentCategories)) {
                    $parentCategory = $parentCategories[$childCategory->getParentId()];

                    if ($parentCategory instanceof CategoryResponseModel) {
                        if (null === $childrenLimit || count($parentCategory->getChildrenCategories()) < $childrenLimit) {
                            $parentCategory->addChildCategory($childCategory);
                        }
                    }
                }
            },
            $childrenCategories
        );

        return array_values($parentCategories);
    }
}