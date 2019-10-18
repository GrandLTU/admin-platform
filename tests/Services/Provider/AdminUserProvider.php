<?php

declare(strict_types=1);

namespace App\Tests\Services\Provider;

use App\Tests\Services\MissingReferenceException;
use App\Tests\Services\ReferencesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Platform\Bundle\AdminBundle\Model\AdminUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * Class AdminUserProvider.
 */
final class AdminUserProvider
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FactoryInterface
     */
    private $adminUserFactory;

    /**
     * @var ReferencesInterface
     */
    private $references;

    /**
     * @var LocaleProvider
     */
    private $localeProvider;

    /**
     * AdminUserProvider constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FactoryInterface $adminUserFactory
     * @param ReferencesInterface $references
     * @param LocaleProvider $localeProvider
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FactoryInterface $adminUserFactory,
        ReferencesInterface $references,
        LocaleProvider $localeProvider
    ) {
        $this->entityManager = $entityManager;
        $this->adminUserFactory = $adminUserFactory;
        $this->references = $references;
        $this->localeProvider = $localeProvider;
    }

    /**
     * @param string $username
     * @param string|null $locale
     *
     * @return AdminUserInterface
     */
    public function getByUsernameAndLocale(string $username, ?string $locale = null): AdminUserInterface
    {
        try {
            return $this->references->get(AdminUserInterface::class, $username);
        } catch (MissingReferenceException $e) {
            // Continue
        }

        /** @var AdminUserInterface $user */
        $user = $this->adminUserFactory->createNew();

        $user->setUsername($username);
        $user->setEmail($username . '@example.com');
        $user->setPlainPassword($username);
        $user->setLocaleCode($this->localeProvider->getByCode($locale)->getCode());
        $user->setEnabled(true);

        $this->entityManager->persist($user);
        $this->references->set(AdminUserInterface::class, $username, $user);

        return $user;
    }
}
