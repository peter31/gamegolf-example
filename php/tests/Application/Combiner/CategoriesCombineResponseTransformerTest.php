<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Combiner\CategoriesCombiner;
use App\Application\Combiner\CategoriesCombinerInterface;
use App\Application\ResponseModel\CategoryResponseModel;
use PHPUnit\Framework\TestCase;

class CategoriesCombinerTest extends TestCase
{
    /** @test */
    public function construct()
    {
        $combiner = new CategoriesCombiner();
        $this->assertInstanceOf(CategoriesCombinerInterface::class, $combiner);
    }

    /** @test */
    public function transformParentWithoutParentsAndChildren()
    {
        $combiner = new CategoriesCombiner();
        $result = $combiner->execute([], []);

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    /** @test */
    public function transformParentWithoutParents()
    {
        $combiner = new CategoriesCombiner();
        $result = $combiner->execute(
            [],
            [
                (new CategoryResponseModel())->setCategoryId(10)->setParentId(2)
            ]
        );

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    /** @test */
    public function transformParentWithoutAnyChildren()
    {
        $combiner = new CategoriesCombiner();
        $result = $combiner->execute(
            [
                (new CategoryResponseModel())->setCategoryId(1)
            ],
            []
        );

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertCount(0, $result[0]->getChildrenCategories());
    }

    /** @test */
    public function transformParentWithoutAssignedChildren()
    {
        $combiner = new CategoriesCombiner();
        $result = $combiner->execute(
            [
                (new CategoryResponseModel())->setCategoryId(1)
            ],
            [
                (new CategoryResponseModel())->setCategoryId(10)->setParentId(2)
            ]
        );

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertCount(0, $result[0]->getChildrenCategories());
    }

    /** @test */
    public function transformParentWithChildren()
    {
        $combiner = new CategoriesCombiner();
        $result = $combiner->execute(
            [
                (new CategoryResponseModel())->setCategoryId(1)
            ],
            [
                (new CategoryResponseModel())->setCategoryId(10)->setParentId(1),
                (new CategoryResponseModel())->setCategoryId(11)->setParentId(1),
                (new CategoryResponseModel())->setCategoryId(12)->setParentId(2),
            ]
        );

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(CategoryResponseModel::class, $result[0]);
        $this->assertEquals(1, $result[0]->getCategoryId());

        $children = $result[0]->getChildrenCategories();
        $this->assertCount(2, $children);
        $this->assertEquals(10, $children[0]->getCategoryId());
        $this->assertEquals(11, $children[1]->getCategoryId());
    }

    /** @test */
    public function transformParentsWithChildren()
    {
        $combiner = new CategoriesCombiner();
        $result = $combiner->execute(
            [
                (new CategoryResponseModel())->setCategoryId(1),
                (new CategoryResponseModel())->setCategoryId(2),
            ],
            [
                (new CategoryResponseModel())->setCategoryId(10)->setParentId(1),
                (new CategoryResponseModel())->setCategoryId(11)->setParentId(1),
                (new CategoryResponseModel())->setCategoryId(12)->setParentId(2),
                (new CategoryResponseModel())->setCategoryId(13)->setParentId(2),
                (new CategoryResponseModel())->setCategoryId(14)->setParentId(3),
            ]
        );

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(CategoryResponseModel::class, $result[0]);
        $this->assertEquals(1, $result[0]->getCategoryId());
        $this->assertInstanceOf(CategoryResponseModel::class, $result[1]);
        $this->assertEquals(2, $result[1]->getCategoryId());

        $children0 = $result[0]->getChildrenCategories();
        $this->assertCount(2, $children0);
        $this->assertEquals(10, $children0[0]->getCategoryId());
        $this->assertEquals(11, $children0[1]->getCategoryId());

        $children1 = $result[1]->getChildrenCategories();
        $this->assertCount(2, $children1);
        $this->assertEquals(12, $children1[0]->getCategoryId());
        $this->assertEquals(13, $children1[1]->getCategoryId());
    }

    /** @test */
    public function transformParentsWithChildrenAndChildrenLimit()
    {
        $combiner = new CategoriesCombiner();
        $result = $combiner->execute(
            [
                (new CategoryResponseModel())->setCategoryId(1),
                (new CategoryResponseModel())->setCategoryId(2),
            ],
            [
                (new CategoryResponseModel())->setCategoryId(10)->setParentId(1),
                (new CategoryResponseModel())->setCategoryId(11)->setParentId(1),
                (new CategoryResponseModel())->setCategoryId(12)->setParentId(2),
                (new CategoryResponseModel())->setCategoryId(13)->setParentId(2),
                (new CategoryResponseModel())->setCategoryId(14)->setParentId(3),
            ],
            1
        );

        $this->assertCount(2, $result);
        $this->assertCount(1, $result[0]->getChildrenCategories());
        $this->assertCount(1, $result[1]->getChildrenCategories());
    }
}
