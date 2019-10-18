<?php

declare(strict_types=1);

namespace App\Tests\Services;

/**
 * Class References.
 */
final class References implements ReferencesInterface
{
    /** @var array */
    private $references = [];

    public function clear(): void
    {
        $this->references = [];
    }

    /**
     * @param string $class
     * @param string $name
     * @param mixed $value
     */
    public function set(string $class, string $name, $value): void
    {
        $this->references[$class][$name] = $value;
    }

    /**
     * @param string $class
     * @param string $name
     *
     * @return mixed
     *
     * @throws MissingReferenceException
     */
    public function get(string $class, string $name)
    {
        if (false === \array_key_exists($name, $this->references[$class] ?? [])) {
            throw new MissingReferenceException(sprintf('Reference %s of class %s was not stored', $name, $class));
        }

        return $this->references[$class][$name];
    }
}
