<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

use App\Application\ResponseModel\NewsResponseModel;
use App\Common\Wordpress\Post\Domain\WpPost;

class NewsResponseTransformer implements NewsResponseTransformerInterface
{
    use ResponseTransformerListTrait;

    public function transform(array $items, ?string $languageCode = null): array
    {
        return array_map(
            function(WpPost $post) {
                $newsModel = new NewsResponseModel();
                $newsModel->setId($post->postId());
                $newsModel->setTitle($post->title());
                $newsModel->setDescription($this->prepareText($post->description(), 128));
                $newsModel->setDate($post->publishedOn()->value());
                $newsModel->setLink($post->href()->value());
                if (is_array($post->images()) && count($post->images()) > 0) {
                    $newsModel->setImage($post->images()[0]);
                }

                return $newsModel;
            },
            array_filter($items, function(WpPost $item) {
                return null !== $item->title() && strlen($item->title()) > 0;
            })
        );
    }

    private function prepareText(string $string, int $length): string
    {
        $string = strip_tags($string);
        if (mb_strlen($string) <= $length) {
            return $string;
        }

        $string = trim(mb_substr($string, 0, $length + 1));

        $lastSpacePosition = mb_strrpos($string, ' ');

        return $lastSpacePosition ? mb_substr($string, 0, $lastSpacePosition) : $string;
    }
}
