<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

use PHPUnit\Framework\TestCase;

class ValueSetterTraitTest extends TestCase
{
    private $object;

    public function setUp(): void
    {
        $this->object = new class() {
            use ValueSetterTrait;

            public function testSetValue($target, $source, string $targetKey, ?string $sourceKey = null)
            {
                $this->setValue($target, $source, $targetKey, $sourceKey);
            }

            public function testSetDateTimeValue($target, $source, string $targetKey, ?string $sourceKey = null)
            {
                $this->setDateTimeValue($target, $source, $targetKey, $sourceKey);
            }
        };
    }

    /** @test */
    public function setValueNotExists()
    {
        $target = new \stdClass();
        $source = new \stdClass();

        $this->object->testSetValue($target, $source, 'id', 'id');

        $this->assertFalse(property_exists($target, 'id'));
    }

    /** @test */
    public function setValueNoChange()
    {
        $target = new \stdClass();
        $target->id = 0;
        $source = new \stdClass();

        $this->object->testSetValue($target, $source, 'id', 'id');

        $this->assertEquals(0, $target->id);
    }

    /** @test */
    public function setValueObjectChange()
    {
        $target = new \stdClass();
        $target->id = 0;
        $source = new \stdClass();
        $target->id = 1;

        $this->object->testSetValue($target, $source, 'id', 'id');

        $this->assertEquals(1, $target->id);
    }

    /** @test */
    public function setValueObjectNoChangeSourceArray()
    {
        $target = new \stdClass();
        $target->id = 0;
        $source = [];

        $this->object->testSetValue($target, $source, 'id', '[id]');

        $this->assertEquals(0, $target->id);
    }

    /** @test */
    public function setValueObjectChangeSourceArray()
    {
        $target = new \stdClass();
        $target->id = 0;
        $source = ['id' => 1];

        $this->object->testSetValue($target, $source, 'id', '[id]');

        $this->assertEquals(1, $target->id);
    }

    /** @test */
    public function setDateTimeValueNotExists()
    {
        $target = new \stdClass();
        $source = new \stdClass();

        $this->object->testSetDateTimeValue($target, $source, 'date_field', 'date_field');

        $this->assertFalse(property_exists($target, 'date_field'));
    }

    /** @test */
    public function setDateTimeValueSuccess()
    {
        $target = new \stdClass();
        $target->date_field = null;
        $source = new \stdClass();
        $source->date_field = '2020-01-01 10:01:02';

        $this->object->testSetDateTimeValue($target, $source, 'date_field', 'date_field');

        $this->assertEquals('2020-01-01 10:01:02', $target->date_field->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function setDateTimeValueSuccessDateTime()
    {
        $target = new \stdClass();
        $target->date_field = null;
        $source = new \stdClass();
        $source->date_field = new \DateTime('2020-01-01 10:01:02');

        $this->object->testSetDateTimeValue($target, $source, 'date_field', 'date_field');

        $this->assertEquals('2020-01-01 10:01:02', $target->date_field->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function setDateTimeValueWrongValue()
    {
        $target = new \stdClass();
        $target->date_field = null;
        $source = new \stdClass();
        $source->date_field = 100;

        $this->object->testSetDateTimeValue($target, $source, 'date_field', 'date_field');

        $this->assertNull($target->date_field);
    }
}
