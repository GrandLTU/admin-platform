<?php

declare(strict_types=1);

namespace App\Tests\Services;

/**
 * Class ReferencesInterface.
 */
interface ReferencesInterface
{
    public function clear(): void;

    /**
     * @param string $class
     * @param string $name
     * @param mixed $value
     */
    public function set(string $class, string $name, $value): void;

    /**
     * @param string $class
     * @param string $name
     *
     * @return mixed
     *
     * @throws MissingReferenceException
     */
    public function get(string $class, string $name);
}
