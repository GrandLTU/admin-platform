<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Sylius UI uses imagine filter and page crashes even if filter is never called
 */
final class ImaginePlaceholderExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'imagine_filter',
                static function () {
                    throw new \RuntimeException('Imagine not available');
                }
            ),
        ];
    }
}
