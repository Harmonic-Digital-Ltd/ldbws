<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class DepartureItemWithCallingPoints
{
    public function __construct(
        public ServiceItemWithCallingPoints $service,
        public string $crs,
    ) {}
}
