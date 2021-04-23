<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

use Symfony\Component\PropertyAccess\PropertyAccessor;

trait ValueSetterTrait
{
    protected $propertyAccessor;

    public function setupPropertyAccessor()
    {
        if (null === $this->propertyAccessor) {
            $this->propertyAccessor = new PropertyAccessor();
        }
    }

    protected function setValue($target, $source, string $targetKey, ?string $sourceKey = null): void
    {
        $this->setupPropertyAccessor();

        if (null === $sourceKey) {
            $sourceKey = $targetKey;
        }

        if ($this->propertyAccessor->isReadable($source, $sourceKey) && $this->propertyAccessor->isWritable($target, $targetKey)) {
            $this->propertyAccessor->setValue($target, $targetKey, $this->propertyAccessor->getValue($source, $sourceKey));
        }
    }

    protected function setDateTimeValue($target, $source, string $targetKey, ?string $sourceKey = null): void
    {
        $this->setupPropertyAccessor();

        if (null === $sourceKey) {
            $sourceKey = $targetKey;
        }

        if ($this->propertyAccessor->isReadable($source, $sourceKey) && $this->propertyAccessor->isWritable($target, $targetKey)) {
            $sourceDate = $this->propertyAccessor->getValue($source, $sourceKey);

            if (null !== $sourceDate) {
                if ($sourceDate instanceof \DateTime) {
                    $this->propertyAccessor->setValue($target, $targetKey, $sourceDate);
                } else {
                   try {
                        $dateValue = new \DateTime((string) $sourceDate);
                    } catch (\Exception $ex) {
                        $dateValue = null;
                    }

                    $this->propertyAccessor->setValue($target, $targetKey, $dateValue);
                }
            }
        }
    }
}
