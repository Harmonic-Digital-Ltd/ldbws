<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class DepartureItem
{
    public function __construct(
        /** @var list<ServiceItem> */
        public array $service,
        public string $crs,
    ) {}
}
