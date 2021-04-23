<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

interface ItemsModelResponseTransformerInterface extends ResponseTransformerInterface
{
    public function transform(array $items, string $languageCode): array;
}
