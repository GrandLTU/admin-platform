<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Class AdminContext.
 */
class AdminContext extends MinkContext
{
    /**
     * @When /^I login in as "([^"]*)"$/
     * @Given /^I am logged in as "([^"]*)"$/
     *
     * @param string $name
     */
    public function iLoginAsUser(string $name): void
    {
        try {
            $this->getSession()->setBasicAuth($name);
        } catch (UnsupportedDriverActionException $e) {
            $this->visitPath('/');
            $this->getSession()->setCookie('test_auth', $name);
        }
    }

    /**
     * @Then /^I should see "([^"]*)" in grid$/
     *
     * @param string $text
     */
    public function iShouldSeeInGrid(string $text): void
    {
        $this->assertElementContainsText('.ui.sortable.stackable.celled.table', $text);
    }

    /**
     * @Given /^I should not see "([^"]*)" in grid$/
     *
     * @param string $text
     */
    public function iShouldNotSeeInGrid(string $text): void
    {
        $this->assertElementNotContainsText('.ui.sortable.stackable.celled.table', $text);
    }

    /**
     * @Then /^I should see "([^"]*)" flash message$/
     *
     * @param string $text
     */
    public function iShouldSeeFlashMessage(string $text): void
    {
        $this->assertElementContainsText('.sylius-flash-message', $text);
    }

    /**
     * @Then /^I edit "([^"]*)" from grid$/
     *
     * @param string $text
     */
    public function iEditFromGrid(string $text): void
    {
        $this->getSession()->getPage()->find(
            'xpath',
            "//tr[@class=\"item\"]/td[text()[contains(., \"{$text}\")]]/../"
            . 'td/descendant::a[text()[contains(., "Edit")]]'
        )->click();
    }
}
