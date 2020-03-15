<?php

namespace Test\Persistence\Mapping\Property;

use Exception;
use InvalidArgumentException;
use Persistence\Mapping\Property\DateTimeProperty;

/**
 * Class DateTimePropertyTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 *
 * @method DateTimeProperty create(...$args)
 */
class DateTimePropertyTest extends AbstractPropertyTest
{
    protected $class = 'Persistence\Mapping\Property\DateTimeProperty';

    public function provideConvertToPhPValue(): array
    {
        return [
            [null, null],
            ['2020-01-01', new \DateTime('2020-01-01')],
            ['1983-04-07', new \DateTime('1983-04-07')],
        ];
    }

    public function provideConvertToDatabaseValue(): array
    {
        return [
            [new \DateTime('2020-01-01'), '2020-01-01'],
            [new \DateTime('1983-04-07'), '1983-04-07'],
        ];
    }

    public function test_convertToPhPValue_withInvalidData(): void
    {
        $this->skipIfMethodIsNotDefined('convertToPhPValue');

        $property = $this->create('Test');

        $message =
            "$this->class::convertToPhpValue() method should throw a ".
            "InvalidArgumentException if it fails to create the DateTime object.";

        try {
            $property->convertToPhpValue(123);
        } catch (Exception $e) {
            $this->assertInstanceOf(
                InvalidArgumentException::class, $e,
                $message
            );

            return;
        }

        $this->fail($message);
    }

    public function test_convertToDatabaseValue_withInvalidData(): void
    {
        $this->skipIfMethodIsNotDefined('convertToDatabaseValue');

        $property = $this->create('Test');

        $message =
            "$this->class::convertToDatabaseValue() method should throw a ".
            "InvalidArgumentException if 'data' argument value is not an instance of DateTime.";

        try {
            $property->convertToDatabaseValue(123);
        } catch (Exception $e) {
            $this->assertInstanceOf(
                InvalidArgumentException::class, $e,
                $message
            );

            return;
        }

        $this->fail($message);
    }
}
