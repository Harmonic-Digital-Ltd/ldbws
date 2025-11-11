<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Exception;

final class UnparseableResponseException extends \RuntimeException
{
    /** @param class-string $class */
    public function __construct(private readonly string $class, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(
            \sprintf('Unable to parse class "%s": %s', $this->class, $previous?->getMessage() ?? 'Unknown'),
            $code,
            $previous,
        );
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
