<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

abstract readonly class BaseStationBoard implements Station
{
    public function __construct(
        public \DateTimeImmutable $generatedAt,
        public string $locationName,
        public string $crs,
        public ?string $filterLocationName = null,
        public ?string $filterCrs = null,
        public ?FilterType $filterType = null,
        /** @var list<NRCCMessage> */
        public array $nrccMessages = [],
        public bool $platformAvailable = false,
        public bool $areServicesAvailable = true,
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
