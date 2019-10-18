<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Tests\Services\Provider\AdminUserProvider;
use App\Tests\Services\Provider\LocaleProvider;
use Behat\Behat\Context\Context;

/**
 * Class FixtureContext.
 */
class FixtureContext implements Context
{
    /**
     * @var LocaleProvider
     */
    private $localeProvider;

    /**
     * @var AdminUserProvider
     */
    private $adminUserProvider;

    /**
     * FixtureContext constructor.
     *
     * @param AdminUserProvider $adminUserProvider
     * @param LocaleProvider $localeProvider
     */
    public function __construct(AdminUserProvider $adminUserProvider, LocaleProvider $localeProvider)
    {
        $this->localeProvider = $localeProvider;
        $this->adminUserProvider = $adminUserProvider;
    }

    /**
     * @Given /there is an admin user "([^"]*)"$/
     * @Given /there is an admin user "([^"]*)" with locale "([^"]*)"$/
     *
     * @param string $name
     * @param string|null $locale
     */
    public function thereIsAnAdminUser(string $name, string $locale = null): void
    {
        $this->adminUserProvider->getByUsernameAndLocale($name, $locale);
    }

    /**
     * @Given /there is a locale$/
     * @Given /there is a locale "([^"]*)"$/
     *
     * @param string|null $locale
     */
    public function thereIsALocale(string $locale = null): void
    {
        $this->localeProvider->getByCode($locale);
    }
}
