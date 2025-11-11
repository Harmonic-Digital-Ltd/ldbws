<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class ServiceItemWithCallingPoints extends ServiceItem
{
    /**
     * @param list<ServiceLocation> $origin
     * @param list<ServiceLocation> $destination
     * @param list<ServiceLocation> $currentOrigins
     * @param list<ServiceLocation> $currentDestinations
     * @param list<string>          $adhocAlerts
     */
    public function __construct(
        public string $serviceID,
        /** @var list<ServiceLocation> */
        public array $origin = [],
        /** @var list<ServiceLocation> */
        public array $destination = [],
        /** @var list<ServiceLocation> */
        public array $currentOrigins = [],
        /** @var list<ServiceLocation> */
        public array $currentDestinations = [],
        public ?string $rsid = null,
        public ?string $sta = null,
        public ?string $eta = null,
        public ?string $std = null,
        public ?string $etd = null,
        public ?string $platform = null,
        public ?string $operator = null,
        public ?string $operatorCode = null,
        public bool $isCircularRoute = false,
        public bool $isCancelled = false,
        public bool $filterLocationCancelled = false,
        public ?ServiceType $serviceType = null,
        public int $length = 0,
        public bool $detachFront = false,
        public bool $isReverseFormation = false,
        public ?string $cancelReason = null,
        public ?string $delayReason = null,
        /** @var list<string> */
        public array $adhocAlerts = [],
        public ?FormationData $formation = null,
        /** @var list<ArrayOfCallingPoints> */
        public array $previousCallingPoints = [],
        /** @var list<ArrayOfCallingPoints> */
        public array $subsequentCallingPoints = [],
    ) {}
}
