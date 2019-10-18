<?php

declare(strict_types=1);

namespace App\Tests\Services;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Class SchemaManager.
 */
final class TestDatabaseManager
{
    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ReferencesInterface
     */
    private $references;

    /**
     * @var SchemaTool
     */
    private $schemaTool;

    /**
     * @var ORMPurger
     */
    private $purger;

    /**
     * DbIsolationContext constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ReferencesInterface $references
     * @param SchemaTool $schemaTool
     * @param ORMPurger $purger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ReferencesInterface $references,
        SchemaTool $schemaTool,
        ORMPurger $purger
    ) {
        $this->entityManager = $entityManager;
        $this->references = $references;
        $this->schemaTool = $schemaTool;
        $this->purger = $purger;
    }

    public function initialize(): void
    {
        if ($this->initialized) {
            return;
        }
        $this->initialized = true;

        $connection = $this->entityManager->getConnection();
        $this->createDatabase($connection->getParams());
        $this->updateSchema();
        $this->purge();
    }

    public function purge(): void
    {
        $this->purger->purge();
        $this->references->clear();
    }

    /**
     * @param array $params
     */
    private function createDatabase(array $params): void
    {
        $dbName = $params['dbname'];
        unset($params['url'], $params['dbname']);

        $connection = DriverManager::getConnection($params);
        $schema = $connection->getSchemaManager();

        if (\in_array($dbName, $schema->listDatabases(), true)) {
            return;
        }

        $schema->createDatabase($dbName);

        $connection->close();
    }

    private function updateSchema(): void
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->updateSchema($metadata, true);
    }
}
