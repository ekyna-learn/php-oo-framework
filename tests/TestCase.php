<?php

namespace Test;

use DateTime;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionProperty;
use RuntimeException;

/**
 * Class TestCase
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class TestCase extends BaseTestCase
{
    protected const REPOSITORY_CLASS = 'Persistence\Repository\EntityRepository';
    protected const MANAGER_CLASS    = 'Persistence\Manager\EntityManager';
    protected const MAPPING_CLASS    = 'Persistence\Mapping\Mapping';

    protected const PRIVATE       = 'private';
    protected const PROTECTED     = 'protected';
    protected const PUBLIC        = 'public';

    /** @var string */
    protected $class;

    protected function skipIfClassDoesNotExist(string $class = null)
    {
        if (empty($class)) {
            if (empty($this->class)) {
                throw new RuntimeException("Please set the class.");
            }

            $class = $this->class;
        }

        if (!class_exists($class)) {
            $this->skipOrFail("Class $class does not exists.");
        }
    }

    protected function getReflectionClass(): ReflectionClass
    {
        if (empty($this->class)) {
            throw new RuntimeException("Please set the class.");
        }

        $this->skipIfClassDoesNotExist($this->class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $reflection = new ReflectionClass($this->class);

        return $reflection;
    }

    protected function assertConstructor(array $arguments = []): ReflectionMethod
    {
        $classRef = $this->getReflectionClass();

        $methodRef = $classRef->getConstructor();

        $this->assertNotNull($methodRef, sprintf("Class %s should have a constructor", $this->class));

        $this->assertArguments($methodRef, $arguments);

        return $methodRef;
    }

    protected function assertConstructorInitialize(object $object, string $property, $value): void
    {
        $rp = $this->getReflectionProperty($property);
        $rp->setAccessible(true);
        $this->assertSame(
            $value, $rp->getValue($object),
            "$this->class::__construct should initialize '$property' property with the '$property' argument value"
        );
    }

    protected function getReflectionConstant(string $constant): ReflectionClassConstant
    {
        $reflection = $this->getReflectionClass();

        if (!$reflection->hasConstant($constant)) {
            $this->skipOrFail(sprintf("Class %s should have a '%s' constant", $this->class, $constant));
        }

        return $reflection->getReflectionConstant($constant);
    }

    protected function assertConstant(
        string $constant,
        $visibility,
        $value
    ): ReflectionClassConstant {
        $reflection = $this->getReflectionConstant($constant);

        $this->assertVisibility($reflection, $visibility);

        $this->assertEquals(
            $value,
            $reflection->getValue(),
            sprintf("%s::%s constant value should be: %s", $this->class, $constant, $value)
        );

        return $reflection;
    }

    protected function skipIfPropertyIsNotDefined(string $property): void
    {
        $reflection = $this->getReflectionClass();

        if (!$reflection->hasProperty($property)) {
            $this->skipOrFail(sprintf("Class %s should have a '%s' property", $this->class, $property));
        }
    }

    protected function getReflectionProperty(string $property): ReflectionProperty
    {
        $this->skipIfPropertyIsNotDefined($property);

        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->getReflectionClass()->getProperty($property);
    }

    protected function assertProperty(
        string $property,
        $visibility,
        bool $static = false
    ): ReflectionProperty {
        $reflection = $this->getReflectionProperty($property);

        $this->assertVisibility($reflection, $visibility);

        if ($static) {
            $this->assertTrue(
                $reflection->isStatic(),
                sprintf("Property %s::%s should be static", $this->class, $property)
            );
        }

        return $reflection;
    }

    protected function skipIfMethodIsNotDefined(string $method): void
    {
        $reflection = $this->getReflectionClass();

        if (!$reflection->hasMethod($method)) {
            $this->skipOrFail(sprintf("Class %s should have a '%s' method", $this->class, $method));
        }
    }

    protected function getReflectionMethod(string $method): ReflectionMethod
    {
        $this->skipIfMethodIsNotDefined($method);

        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->getReflectionClass()->getMethod($method);
    }

    protected function assertMethod(
        string $method,
        string $visibility,
        array $arguments = [],
        string $return = null,
        bool $orNull = false,
        bool $static = false
    ): ReflectionMethod {
        $reflection = $this->getReflectionMethod($method);

        $this->assertVisibility($reflection, $visibility);

        $this->assertArguments($reflection, $arguments);

        if ($return) {
            $retRef = $reflection->getReturnType();

            $this->assertNotNull($retRef, sprintf(
                "%s::%s() method should return '%s'.",
                $this->class, $method, $return
            ));

            if ($retRef) {
                if ('self' === $returned = (string)$retRef) {
                    $returned = $this->class;
                }
                $this->assertEquals(
                    $return, $returned,
                    sprintf(
                        "%s::%s() method should return '%s'.",
                        $this->class, $method, $return
                    )
                );
            }

            if ($orNull) {
                $this->assertTrue(
                    $retRef->allowsNull(),
                    sprintf(
                        "%s::%s() method should allow to return null (add '?' before the return type).",
                        $this->class, $method
                    )
                );
            }
        }

        if ($static) {
            $this->assertTrue(
                $reflection->isStatic(),
                sprintf(
                    "%s::%s() method should be static.",
                    $this->class, $method
                )
            );
        }

        return $reflection;
    }

    protected function conversionErrorMessage(string $method, array $values, $actual): string
    {
        return sprintf(
            "%s::%s should convert %s into %s, %s returned.",
            $this->class,
            $method,
            $this->valueToString($values[0]),
            $this->valueToString($values[1]),
            $this->valueToString($actual)
        );
    }

    protected function valueToString($value)
    {
        if (is_null($value)) {
            return "'NULL'";
        }

        if (is_string($value)) {
            return "'$value'";
        }

        if (is_bool($value)) {
            return $value ? "'TRUE'" : "'FALSE'";
        }

        if (is_int($value)) {
            return "'(int) $value'";
        }

        if ($value instanceof DateTime) {
            return "'(DateTime) {$value->format('Y-m-d')}'";
        }

        throw new InvalidArgumentException("Unexpected value.");
    }

    private function skipOrFail(string $message)
    {
        if (isset($_SERVER['argv']) && in_array('--teamcity', $_SERVER['argv'], true)) {
            $this->markTestSkipped($message);
        } else {
            $this->fail($message);
        }
    }

    private function assertVisibility($reflection, $visibility): void
    {
        if (empty($visibility)) {
            return;
        }

        if (
            !$reflection instanceof ReflectionClassConstant
            && !$reflection instanceof ReflectionProperty
            && !$reflection instanceof ReflectionMethod
        ) {
            throw new LogicException(
                "Expected instance of ReflectionClassConstant, ReflectionProperty or ReflectionMethod"
            );
        }

        if ($reflection instanceof ReflectionClassConstant) {
            $type = 'constant';
        } elseif ($reflection instanceof ReflectionProperty) {
            $type = 'property';
        } elseif ($reflection instanceof ReflectionMethod) {
            $type = 'method';
        } else {
            throw new LogicException(
                "Expected instance of ReflectionClassConstant, ReflectionProperty or ReflectionMethod"
            );
        }

        if (is_string($visibility)) {
            $visibility = [$visibility];
        }

        $test = false;
        foreach ($visibility as $v) {
            switch ($v) {
                case self::PRIVATE:
                    if ($reflection->isPrivate()) {
                        $test = true;
                        break 2;
                    }
                    break;
                case self::PROTECTED:
                    if ($reflection->isProtected()) {
                        $test = true;
                        break 2;
                    }
                    break;
                case self::PUBLIC:
                    if ($reflection->isPublic()) {
                        $test = true;
                        break 2;
                    }
                    break;
                default:
                    throw new InvalidArgumentException("Unexpected visibility.");
            }
        }

        if (1 === $count = count($visibility)) {
            $options = reset($visibility);
        } else {
            $options = implode(', ', array_slice($visibility, 0, $count - 1)) . ' or ' . end($visibility);
        }

        $this->assertTrue($test, sprintf(
            "%s::%s %s should be %s.",
            $this->class, $reflection->getName(), $type, $options
        ));
    }

    private function assertArguments(ReflectionMethod $method, array $arguments): void
    {
        if (empty($arguments)) {
            return;
        }

        $parameters = $method->getParameters();
        foreach ($arguments as $index => $argument) {
            $argument = array_replace([
                'name'     => null,
                'type'     => null,
                'nullable' => false,
            ], $argument);


            $this->assertArrayHasKey($index, $parameters, sprintf(
                "%s::%s() method should have a #%s argument",
                $this->class, $method->getName(), $index
            ));

            $parameter = $parameters[$index];

            $name = "#$index";
            if ($argument['name']) {
                $this->assertEquals($argument['name'], $parameter->getName(), sprintf(
                    "Argument #%s of %s::%s() method should be named '%s'",
                    $index, $this->class, $method->getName(), $argument['name']
                ));
                $name = "'{$argument['name']}'";
            }

            if ($argument['type']) {
                $type = $parameter->getType();
                $this->assertEquals($type, (string)$type, sprintf(
                    "Argument %s of %s::%s() method should be of type '%s'",
                    $name, $this->class, $method->getName(), $argument['type']
                ));

                if ($argument['nullable']) {
                    $this->assertTrue($type->allowsNull(), sprintf(
                        "Argument %s of %s::%s() method should be allow null value (add ' = null' after argument name)",
                        $name, $this->class, $method->getName()
                    ));
                }
            }
        }
    }

    protected function create(...$args)
    {
        $this->skipIfClassDoesNotExist();

        return new $this->class(...$args);
    }
}
