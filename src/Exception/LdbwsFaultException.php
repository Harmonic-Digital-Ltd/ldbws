<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Exception;

final class LdbwsFaultException extends \RuntimeException implements LdbwsException
{
    public function __construct(private string $fault, private string $faultCode, ?\Throwable $previous = null)
    {
        parent::__construct($this->fault, (int) ($previous?->getCode() ?? 0), $previous);
    }

    public function getFaultCode(): string
    {
        return $this->faultCode;
    }

    public function getFault(): string
    {
        return $this->fault;
    }
}
