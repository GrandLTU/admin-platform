<?php

namespace App\Tests\Unit;

use App\Tests\Services\TestDatabaseManager;
use Doctrine\DBAL\Connection;

trait DatabaseTestTrait
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->initDatabase();
    }

    protected function tearDown(): void
    {
        $this->resetDatabase();
        parent::tearDown();
    }

    protected function initDatabase(): void
    {
        /** @var TestDatabaseManager $manager */
        $manager = self::$container->get(TestDatabaseManager::class);
        $manager->initialize();
        /** @var Connection $connection */
        $connection = self::$container->get('database_connection');
        $connection->beginTransaction();
    }

    protected function resetDatabase(): void
    {
        /** @var Connection $connection */
        $connection = self::$container->get('database_connection');
        $connection->rollBack();
    }
}
