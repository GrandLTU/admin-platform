<?php

declare(strict_types=1);

namespace App\Tests\Services;

use Exception;
use Throwable;

/**
 * Class MissingReferenceException.
 */
class MissingReferenceException extends Exception
{
    /**
     * MissingReferenceException constructor.
     *
     * @param string $message
     * @param Throwable|null $previous
     * @param int $code
     */
    public function __construct(string $message = '', ?Throwable $previous = null, int $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
