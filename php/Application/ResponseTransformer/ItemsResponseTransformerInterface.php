<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

interface ItemsResponseTransformerInterface extends ResponseTransformerInterface
{
    public function transform(array $items, string $languageCode): array;
}
