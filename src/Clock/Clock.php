<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Clock;

use Psr\Clock\ClockInterface;

final class Clock implements ClockInterface
{
    #[\Override]
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
