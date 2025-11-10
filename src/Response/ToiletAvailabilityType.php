<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class ToiletAvailabilityType
{
    public function __construct(
        public string $status,
        public string $value,
    ) {}
}
