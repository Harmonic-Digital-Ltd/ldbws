<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

/**
 * Root model for realtime trains details response.
 */
final readonly class StationBoardWithDetails implements Station
{
    public function __construct(
        public \DateTimeImmutable $generatedAt,
        public string $locationName,
        public string $crs,
        /** @var list<ServiceItemWithCallingPoints> */
        public array $trainServices = [],
        public bool $areServicesAvailable = true,
        public bool $platformAvailable = false,
        public ?string $filterType = null,
        public ?string $filterLocationName = null,
        public ?string $filterCrs = null,
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
