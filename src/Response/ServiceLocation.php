<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class ServiceLocation implements Station
{
    public function __construct(
        public string $locationName,
        public string $crs,
        public ?string $via = null,
        public ?string $futureChangeTo = null,
        public bool $assocIsCancelled = false,
    ) {}

    #[\Override]
    public function getName(): string
    {
        return $this->locationName;
    }

    #[\Override]
    public function getCrs(): string
    {
        return $this->crs;
    }
}
