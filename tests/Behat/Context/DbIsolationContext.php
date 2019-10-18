<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Tests\Services\FlushingReferences;
use App\Tests\Services\TestDatabaseManager;
use Behat\Behat\Context\Context;

/**
 * Class DbIsolationContext.
 */
class DbIsolationContext implements Context
{
    /**
     * @var TestDatabaseManager
     */
    private $databaseManager;

    /**
     * @var FlushingReferences
     */
    private $references;

    /**
     * DbIsolationContext constructor.
     *
     * @param TestDatabaseManager $databaseManager
     * @param FlushingReferences $references
     */
    public function __construct(TestDatabaseManager $databaseManager, FlushingReferences $references)
    {
        $this->databaseManager = $databaseManager;
        $this->references = $references;
    }

    /**
     * @BeforeScenario
     */
    public function initializeDatabase(): void
    {
        $this->databaseManager->initialize();
    }

    /**
     * @AfterScenario
     */
    public function afterScenario(): void
    {
        $this->databaseManager->purge();
    }

    /**
     * @AfterStep
     */
    public function afterStep(): void
    {
        $this->references->flush();
    }
}
