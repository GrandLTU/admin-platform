<?php

declare(strict_types=1);

namespace App\Tests\Services\Provider;

use App\Tests\Services\MissingReferenceException;
use App\Tests\Services\ReferencesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * Class LocaleProvider.
 */
final class LocaleProvider
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FactoryInterface
     */
    private $localeFactory;

    /**
     * @var ReferencesInterface
     */
    private $references;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * LocaleProvider constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FactoryInterface $localeFactory
     * @param ReferencesInterface $references
     * @param string $locale
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FactoryInterface $localeFactory,
        ReferencesInterface $references,
        string $locale
    ) {
        $this->entityManager = $entityManager;
        $this->localeFactory = $localeFactory;
        $this->references = $references;
        $this->defaultLocale = $locale;
    }

    public function getByCode(string $code = null): LocaleInterface
    {
        if (null === $code) {
            $code = $this->defaultLocale;
        }

        try {
            return $this->references->get(LocaleInterface::class, $code);
        } catch (MissingReferenceException $e) {
            // Continue;
        }

        /** @var LocaleInterface $locale */
        $locale = $this->localeFactory->createNew();
        $locale->setCode($code);

        $this->entityManager->persist($locale);
        $this->references->set(LocaleInterface::class, $code, $locale);

        return $locale;
    }
}
