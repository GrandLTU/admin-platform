<?php

declare(strict_types=1);

namespace App\Tests\Services;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class FlushingReferences.
 */
final class FlushingReferences implements ReferencesInterface
{
    /**
     * @var ReferencesInterface
     */
    private $inner;

    /**
     * @var bool
     */
    private $flushNeeded = false;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * FlushingReferences constructor.
     *
     * @param ReferencesInterface $inner
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ReferencesInterface $inner, EntityManagerInterface $entityManager)
    {
        $this->inner = $inner;
        $this->entityManager = $entityManager;
    }

    public function clear(): void
    {
        $this->inner->clear();
        $this->flushNeeded = false;
    }

    /**
     * @param string $class
     * @param string $name
     * @param mixed $value
     */
    public function set(string $class, string $name, $value): void
    {
        $this->inner->set($class, $name, $value);
        $this->flushNeeded = true;
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
        return $this->inner->get($class, $name);
    }

    public function flush(): void
    {
        if ($this->flushNeeded) {
            $this->entityManager->flush();
        }
    }
}
