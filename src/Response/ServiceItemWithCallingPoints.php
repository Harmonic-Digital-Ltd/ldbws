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
        /** @var list<ArrayOfCallingPoints> */
        public array $previousCallingPoints = [],
        /** @var list<ArrayOfCallingPoints> */
        public array $subsequentCallingPoints = [],
        string $serviceID,
        ?FormationData $formation = null,
        array $origin = [],
        array $destination = [],
        array $currentOrigins = [],
        array $currentDestinations = [],
        ?string $rsid = null,
        ?string $sta = null,
        ?string $eta = null,
        ?string $std = null,
        ?string $etd = null,
        ?string $platform = null,
        ?string $operator = null,
        ?string $operatorCode = null,
        bool $isCircularRoute = false,
        bool $isCancelled = false,
        bool $filterLocationCancelled = false,
        ?ServiceType $serviceType = null,
        int $length = 0,
        bool $detachFront = false,
        bool $isReverseFormation = false,
        ?string $cancelReason = null,
        ?string $delayReason = null,
        array $adhocAlerts = [],
    ) {
        parent::__construct(
            serviceID: $serviceID,
            formation: $formation,
            origin: $origin,
            destination: $destination,
            currentOrigins: $currentOrigins,
            currentDestinations: $currentDestinations,
            rsid: $rsid,
            sta: $sta,
            eta: $eta,
            std: $std,
            etd: $etd,
            platform: $platform,
            operator: $operator,
            operatorCode: $operatorCode,
            isCircularRoute: $isCircularRoute,
            isCancelled: $isCancelled,
            filterLocationCancelled: $filterLocationCancelled,
            serviceType: $serviceType,
            length: $length,
            detachFront: $detachFront,
            isReverseFormation: $isReverseFormation,
            cancelReason: $cancelReason,
            delayReason: $delayReason,
            adhocAlerts: $adhocAlerts,
        );
    }
}
