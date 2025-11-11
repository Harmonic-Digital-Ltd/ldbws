<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class DeparturesBoard extends BaseStationBoard
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
        /** @var list<DepartureItem> */
        public array $departures = [],
    ) {}
}
