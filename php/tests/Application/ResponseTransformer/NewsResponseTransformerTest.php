<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

use App\Application\ResponseModel\NewsResponseModel;
use App\Common\Shared\Domain\ValueObject\DateValueObject;
use App\Common\Shared\Domain\ValueObject\ExternalUrl;
use App\Common\Wordpress\Post\Domain\WpPost;
use App\Common\Wordpress\Post\Domain\WpPostId;
use App\Common\Wordpress\Post\Domain\WpPostLocale;
use PHPUnit\Framework\TestCase;

class NewsResponseTransformerTest extends TestCase
{
    /** @test */
    public function transformSuccess()
    {
        $post = new WpPost(
            new WpPostId('fdface7c-267d-40df-91ed-c5fd50ac76c5'),
            1111,
            'test title',
            WpPostLocale::fromBlogId(3),
            new ExternalUrl('http://example.com'),
            'test description',
            ['image_url1'],
            [],
            new DateValueObject(new \DateTime('2021-01-01')),
            new DateValueObject(new \DateTime('2021-01-01')),
            new DateValueObject(new \DateTime('2021-01-01')),
            []
        );

        $transformer = new NewsResponseTransformer();
        $result = $transformer->transform([$post], 'en');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(NewsResponseModel::class, $result[0]);
        $this->assertEquals(1111, $result[0]->getId());
        $this->assertEquals('test title', $result[0]->getTitle());
        $this->assertEquals('test description', $result[0]->getDescription());
        $this->assertEquals('2021-01-01 00:00:00', $result[0]->getDate()->format('Y-m-d H:i:s'));
        $this->assertEquals('http://example.com', $result[0]->getLink());
        $this->assertEquals('image_url1', $result[0]->getImage());
    }
}
