<?php


namespace Yunbuye\ThinkTesting\Traits;


use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

trait AssertIs
{
    /**
     * Asserts that a variable is of type array.
     */
    public static function assertIsArray($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new IsType(IsType::TYPE_ARRAY),
            $message
        );
    }

    /**
     * Asserts that a variable is of type bool.
     */
    public static function assertIsBool($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new IsType(IsType::TYPE_BOOL),
            $message
        );
    }

    /**
     * Asserts that a variable is of type float.
     */
    public static function assertIsFloat($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new IsType(IsType::TYPE_FLOAT),
            $message
        );
    }

    /**
     * Asserts that a variable is of type int.
     */
    public static function assertIsInt($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new IsType(IsType::TYPE_INT),
            $message
        );
    }

    /**
     * Asserts that a variable is of type numeric.
     */
    public static function assertIsNumeric($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new IsType(IsType::TYPE_NUMERIC),
            $message
        );
    }

    /**
     * Asserts that a variable is of type object.
     */
    public static function assertIsObject($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new IsType(IsType::TYPE_OBJECT),
            $message
        );
    }

    /**
     * Asserts that a variable is of type resource.
     */
    public static function assertIsResource($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new IsType(IsType::TYPE_RESOURCE),
            $message
        );
    }

    /**
     * Asserts that a variable is of type string.
     */
    public static function assertIsString($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new IsType(IsType::TYPE_STRING),
            $message
        );
    }

    /**
     * Asserts that a variable is of type scalar.
     */
    public static function assertIsScalar($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new IsType(IsType::TYPE_SCALAR),
            $message
        );
    }

    /**
     * Asserts that a variable is of type callable.
     */
    public static function assertIsCallable($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new IsType(IsType::TYPE_CALLABLE),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type array.
     */
    public static function assertIsNotArray($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_ARRAY)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type bool.
     */
    public static function assertIsNotBool($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_BOOL)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type float.
     */
    public static function assertIsNotFloat($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_FLOAT)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type int.
     */
    public static function assertIsNotInt($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_INT)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type numeric.
     */
    public static function assertIsNotNumeric($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_NUMERIC)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type object.
     */
    public static function assertIsNotObject($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_OBJECT)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type resource.
     */
    public static function assertIsNotResource($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_RESOURCE)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type string.
     */
    public static function assertIsNotString($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_STRING)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type scalar.
     */
    public static function assertIsNotScalar($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_SCALAR)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type callable.
     */
    public static function assertIsNotCallable($actual, string $message = ''): void
    {
        TestCase::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_CALLABLE)),
            $message
        );
    }

}