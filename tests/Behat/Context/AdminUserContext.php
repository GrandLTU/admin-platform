<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Platform\Bundle\AdminBundle\Model\AdminUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * Class AdminUserContext.
 */
class AdminUserContext extends RawMinkContext
{
    /**
     * @var string|null
     */
    private $storedPassword;

    /**
     * @var AdminContext
     */
    private $adminContext;

    /**
     * @var RepositoryInterface
     */
    private $adminUserRepository;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * AdminUserContext constructor.
     *
     * @param AdminContext $adminContext
     * @param RepositoryInterface $adminUserRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        AdminContext $adminContext,
        RepositoryInterface $adminUserRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->adminContext = $adminContext;
        $this->adminUserRepository = $adminUserRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Given /^I am on users page$/
     */
    public function iAmOnUsersPage(): void
    {
        $this->adminContext->visit('/users');
    }

    /**
     * @When /^I change user name to "([^"]*)"$/
     *
     * @param string $name
     */
    public function iChangeUserNameTo(string $name): void
    {
        $this->adminContext->fillField('Username', $name);
        $this->adminContext->pressButton('Save changes');
    }

    /**
     * @Given /^I have written down password hash of "([^"]*)"$/
     *
     * @param string $username
     */
    public function iHaveWrittenDownPasswordHashOf(string $username): void
    {
        /** @var AdminUserInterface $user */
        $user = $this->adminUserRepository->findOneBy(['username' => $username]);
        $this->storedPassword = $user->getPassword();
    }

    /**
     * @Then /^password hash of "([^"]*)" should differ from hash i have written down$/
     *
     * @param string $username
     */
    public function passwordHashOfShouldDifferFromHashIHaveWrittenDown(string $username): void
    {
        Assert::notNull($this->storedPassword, 'Password hash was not stored');
        $this->entityManager->clear();

        /** @var AdminUserInterface $user */
        $user = $this->adminUserRepository->findOneBy(['username' => $username]);

        Assert::notSame($user->getPassword(), $this->storedPassword);
    }
}
