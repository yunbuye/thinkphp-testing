<?php


namespace Xwpd\ThinkTesting\Traits;


use PHPUnit\Framework\TestCase as PHPUnit;
use think\helper\Arr;
use think\helper\Str;
use Xwpd\ThinkTesting\TestCase;

/**
 * Trait AssertJson
 * @package Xwpd\ThinkTesting\Traits
 * @mixin TestCase
 */
trait AssertJson
{
    use AssertIs;

    /**
     * Get the assertion message for assertJson.
     *
     * @param array $data
     * @return string
     */
    protected function assertJsonMessage(array $data)
    {
        $expected = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $actual = json_encode($this->decodeResponseJson(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return 'Unable to find JSON: ' . PHP_EOL . PHP_EOL .
            "[{$expected}]" . PHP_EOL . PHP_EOL .
            'within response JSON:' . PHP_EOL . PHP_EOL .
            "[{$actual}]." . PHP_EOL . PHP_EOL;
    }

    /**
     * Assert that the response has the exact given JSON.
     *
     * @param array $data
     * @return $this
     */
    public function assertExactJson(array $data)
    {
        $actual = json_encode(Arr::sortRecursive(
            (array)$this->decodeResponseJson()
        ));

        PHPUnit::assertEquals(json_encode(Arr::sortRecursive($data)), $actual);

        return $this;
    }

    /**
     * Assert that the response contains the given JSON fragment.
     *
     * @param array $data
     * @return $this
     */
    public function assertJsonFragment(array $data)
    {
        $actual = json_encode(Arr::sortRecursive(
            (array)$this->decodeResponseJson()
        ));

        foreach (Arr::sortRecursive($data) as $key => $value) {
            $expected = $this->jsonSearchStrings($key, $value);

            PHPUnit::assertTrue(
                Str::contains($actual, $expected),
                'Unable to find JSON fragment: ' . PHP_EOL . PHP_EOL .
                '[' . json_encode([$key => $value]) . ']' . PHP_EOL . PHP_EOL .
                'within' . PHP_EOL . PHP_EOL .
                "[{$actual}]."
            );
        }

        return $this;
    }

    /**
     * Assert that the response does not contain the given JSON fragment.
     *
     * @param array $data
     * @param bool $exact
     * @return $this
     */
    public function assertJsonMissing(array $data, $exact = false)
    {
        if ($exact) {
            return $this->assertJsonMissingExact($data);
        }

        $actual = json_encode(Arr::sortRecursive(
            (array)$this->decodeResponseJson()
        ));

        foreach (Arr::sortRecursive($data) as $key => $value) {
            $unexpected = $this->jsonSearchStrings($key, $value);

            PHPUnit::assertFalse(
                Str::contains($actual, $unexpected),
                'Found unexpected JSON fragment: ' . PHP_EOL . PHP_EOL .
                '[' . json_encode([$key => $value]) . ']' . PHP_EOL . PHP_EOL .
                'within' . PHP_EOL . PHP_EOL .
                "[{$actual}]."
            );
        }

        return $this;
    }

    /**
     * Assert that the response does not contain the exact JSON fragment.
     *
     * @param array $data
     * @return $this
     */
    public function assertJsonMissingExact(array $data)
    {
        $actual = json_encode(Arr::sortRecursive(
            (array)$this->decodeResponseJson()
        ));

        foreach (Arr::sortRecursive($data) as $key => $value) {
            $unexpected = $this->jsonSearchStrings($key, $value);

            if (!Str::contains($actual, $unexpected)) {
                return $this;
            }
        }

        PHPUnit::fail(
            'Found unexpected JSON fragment: ' . PHP_EOL . PHP_EOL .
            '[' . json_encode($data) . ']' . PHP_EOL . PHP_EOL .
            'within' . PHP_EOL . PHP_EOL .
            "[{$actual}]."
        );
    }

    /**
     * Get the strings we need to search for when examining the JSON.
     *
     * @param string $key
     * @param string $value
     * @return array
     */
    protected function jsonSearchStrings($key, $value)
    {
        $needle = substr(json_encode([$key => $value]), 1, -1);

        return [
            $needle . ']',
            $needle . '}',
            $needle . ',',
        ];
    }

    /**
     * Assert that the response has a given JSON structure.
     *
     * @param array|null $structure
     * @param array|null $responseData
     * @return $this
     */
    public function assertJsonStructure(array $structure = null, $responseData = null)
    {
        if (is_null($structure)) {
            return $this->assertExactJson($this->json());
        }

        if (is_null($responseData)) {
            $responseData = $this->decodeResponseJson();
        }

        foreach ($structure as $key => $value) {
            if (is_array($value) && $key === '*') {
                static::assertIsArray($responseData);

                foreach ($responseData as $responseDataItem) {
                    $this->assertJsonStructure($structure['*'], $responseDataItem);
                }
            } elseif (is_array($value)) {
                PHPUnit::assertArrayHasKey($key, $responseData);

                $this->assertJsonStructure($structure[$key], $responseData[$key]);
            } else {
                PHPUnit::assertArrayHasKey($value, $responseData);
            }
        }

        return $this;
    }

    /**
     * Assert that the response JSON has the expected count of items at the given key.
     *
     * @param int $count
     * @param string|null $key
     * @return $this
     */
    public function assertJsonCount(int $count, $key = null)
    {
        if ($key) {
            PHPUnit::assertCount(
                $count, static::data_get($this->json(), $key),
                "Failed to assert that the response count matched the expected {$count}"
            );

            return $this;
        }

        PHPUnit::assertCount($count,
            $this->json(),
            "Failed to assert that the response count matched the expected {$count}"
        );

        return $this;
    }

    public static function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }

    public static function collapse($array)
    {
        $results = [];

        foreach ($array as $values) {
            if (!is_array($values)) {
                continue;
            }
            $results[] = $values;
        }
        return array_merge([], ...$results);
    }

    public static function accessible($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    public static function exists($array, $key)
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }

    public static function data_get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while (!is_null($segment = array_shift($key))) {
            if ($segment === '*') {
                if (!is_array($target)) {
                    return static::value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = static::data_get($item, $key);
                }

                return in_array('*', $key) ? static::collapse($result) : $result;
            }

            if (static::accessible($target) && static::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return static::value($default);
            }
        }

        return $target;
    }

    /**
     * If the given value is not an array and not null, wrap it in one.
     *
     * @param mixed $value
     * @return array
     */
    public static function wrap($value)
    {
        if (is_null($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }

    /**
     * Assert that the response has the given JSON validation errors.
     *
     * @param string|array $errors
     * @return $this
     */
    public function assertJsonValidationErrors($errors)
    {
        $errors = static::wrap($errors);

        PHPUnit::assertNotEmpty($errors, 'No validation errors were provided.');

        $jsonErrors = $this->json()['errors'] ?? [];

        $errorMessage = $jsonErrors
            ? 'Response has the following JSON validation errors:' .
            PHP_EOL . PHP_EOL . json_encode($jsonErrors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL
            : 'Response does not have JSON validation errors.';

        foreach ($errors as $key => $value) {
            PHPUnit::assertArrayHasKey(
                (is_int($key)) ? $value : $key,
                $jsonErrors,
                "Failed to find a validation error in the response for key: '{$value}'" . PHP_EOL . PHP_EOL . $errorMessage
            );

            if (!is_int($key)) {
                $hasError = false;

                foreach (static::wrap($jsonErrors[$key]) as $jsonErrorMessage) {
                    if (Str::contains($jsonErrorMessage, $value)) {
                        $hasError = true;

                        break;
                    }
                }

                if (!$hasError) {
                    PHPUnit::fail(
                        "Failed to find a validation error in the response for key and message: '$key' => '$value'" . PHP_EOL . PHP_EOL . $errorMessage
                    );
                }
            }
        }

        return $this;
    }

    /**
     * Assert that the response has no JSON validation errors for the given keys.
     *
     * @param string|array|null $keys
     * @return $this
     */
    public function assertJsonMissingValidationErrors($keys = null)
    {
        if ($this->getContent() === '') {
            PHPUnit::assertTrue(true);

            return $this;
        }

        $json = $this->json();

        if (!array_key_exists('errors', $json)) {
            PHPUnit::assertArrayNotHasKey('errors', $json);

            return $this;
        }

        $errors = $json['errors'];

        if (is_null($keys) && count($errors) > 0) {
            PHPUnit::fail(
                'Response has unexpected validation errors: ' . PHP_EOL . PHP_EOL .
                json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
        }

        foreach (static::wrap($keys) as $key) {
            PHPUnit::assertFalse(
                isset($errors[$key]),
                "Found unexpected validation error for key: '{$key}'"
            );
        }

        return $this;
    }

    /**
     * Validate and return the decoded response JSON.
     *
     * @param string|null $key
     * @return mixed
     */
    public function decodeResponseJson($key = null)
    {
        $decodedResponse = json_decode($this->getContent(), true);

        if (is_null($decodedResponse) || $decodedResponse === false) {
            if (isset($this->exception) && $this->exception) {
                throw $this->exception;
            } else {
                PHPUnit::fail('Invalid JSON was returned from the route.');
            }
        }

        return static::data_get($decodedResponse, $key);
    }

    /**
     * Validate and return the decoded response JSON.
     *
     * @param string|null $key
     * @return mixed
     */
    public function json($key = null)
    {
        return $this->decodeResponseJson($key);
    }
}