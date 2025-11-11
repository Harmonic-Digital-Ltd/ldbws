<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Factory;

use HarmonicDigital\Ldbws\Exception\UnparseableResponseException;
use HarmonicDigital\Ldbws\Response\CoachData;
use HarmonicDigital\Ldbws\Response\DepartureItem;
use HarmonicDigital\Ldbws\Response\FilterType;
use HarmonicDigital\Ldbws\Response\FormationData;
use HarmonicDigital\Ldbws\Response\LoadingCategory;
use HarmonicDigital\Ldbws\Response\NRCCMessage;
use HarmonicDigital\Ldbws\Response\ServiceItem;
use HarmonicDigital\Ldbws\Response\ServiceLocation;
use HarmonicDigital\Ldbws\Response\ServiceType;
use HarmonicDigital\Ldbws\Response\StationBoard;
use HarmonicDigital\Ldbws\Response\ToiletAvailabilityType;
use HarmonicDigital\Ldbws\Response\ToiletStatus;
use HarmonicDigital\Ldbws\Response\ToiletType;

/** @psalm-suppress MixedArgument */
final class ResponseFactory implements ResponseFactoryInterface
{
    #[\Override]
    public function parseStationBoard(array $data): StationBoard
    {
        try {
            return new StationBoard(
                new \DateTimeImmutable($data['generatedAt']),
                (string) $data['locationName'],
                (string) $data['crs'],
                $data['filterLocationName'] ?? null,
                $data['filterCrs'] ?? $data['filtercrs'] ?? null,
                isset($data['filterType']) ? FilterType::from((string) $data['filterType']) : null,
                \array_map($this->parseNRCCMessage(...), \array_values($data['nrccMessages'] ?? [])),
                (bool) ($data['platformAvailable'] ?? false),
                (bool) ($data['areServicesAvailable'] ?? true),
                \array_map($this->parseServiceItem(...), \array_values($data['trainServices'] ?? [])),
                \array_map($this->parseServiceItem(...), \array_values($data['busServices'] ?? [])),
                \array_map($this->parseServiceItem(...), \array_values($data['ferryServices'] ?? [])),
            );
        } catch (\Throwable $e) {
            throw new UnparseableResponseException(StationBoard::class, 0, $e);
        }
    }

    private function parseDepartureItem(array $departureItem): DepartureItem
    {
        return new DepartureItem(
            \array_map($this->parseServiceItem(...), \array_values($departureItem['service'] ?? [])),
            (string) $departureItem['crs'],
        );
    }

    private function parseServiceItem(array $data): ServiceItem
    {
        return new ServiceItem(
            (string) ($data['serviceID'] ?? ''),
            \array_map($this->parseServiceLocation(...), \array_values($data['origin'] ?? [])),
            \array_map($this->parseServiceLocation(...), \array_values($data['destination'] ?? [])),
            \array_map($this->parseServiceLocation(...), \array_values($data['currentOrigins'] ?? [])),
            \array_map($this->parseServiceLocation(...), \array_values($data['currentDestinations'] ?? [])),
            $data['rsid'] ?? null,
            $data['sta'] ?? null,
            $data['eta'] ?? null,
            $data['std'] ?? null,
            $data['etd'] ?? null,
            $data['platform'] ?? null,
            $data['operator'] ?? null,
            $data['operatorCode'] ?? null,
            (bool) ($data['isCircularRoute'] ?? false),
            (bool) ($data['isCancelled'] ?? false),
            (bool) ($data['filterLocationCancelled'] ?? false),
            ServiceType::tryFrom($data['serviceType'] ?? 'train') ?? ServiceType::TRAIN,
            (int) ($data['length'] ?? 0),
            (bool) ($data['detachFront'] ?? false),
            (bool) ($data['isReverseFormation'] ?? false),
            $data['cancelReason'] ?? null,
            $data['delayReason'] ?? null,
            $data['adhocAlerts'] ?? [],
            \is_array($data['formation'] ?? null) ? $this->parseFormationData($data['formation']) : null,
        );
    }

    private function parseServiceLocation(array $data): ServiceLocation
    {
        return new ServiceLocation(
            (string) $data['locationName'],
            (string) $data['crs'],
            $data['via'] ?? null,
            $data['futureChangeTo'] ?? null,
            (bool) ($data['assocIsCancelled'] ?? false),
        );
    }

    private function parseFormationData(array $data): FormationData
    {
        return new FormationData(
            $this->parseLoadingCategory($data['loadingCategory'] ?? null),
            \array_map($this->parseCoachData(...), \array_values($data['coaches'] ?? [])),
        );
    }

    private function parseCoachData(array $data): CoachData
    {
        return new CoachData(
            $data['coachClass'] ?? null,
            $data['number'] ?? null,
            $data['loadingSpecified'] ?? false,
            $data['loading'] ?? null,
            $this->parseToiletAvailabilityType($data['toilet'] ?? null),
        );
    }

    private function parseToiletAvailabilityType(?array $data): ?ToiletAvailabilityType
    {
        if (null === $data) {
            return null;
        }

        return new ToiletAvailabilityType(
            ToiletStatus::tryFrom($data['status'] ?? '') ?? ToiletStatus::UNKNOWN,
            ToiletType::tryFrom($data['value'] ?? '') ?? ToiletType::UNKNOWN,
        );
    }

    private function parseLoadingCategory(?array $data): ?LoadingCategory
    {
        if (null === $data) {
            return null;
        }

        return new LoadingCategory(
            $data['code'] ?? null,
            $data['colour'] ?? null,
            $data['image'] ?? null,
        );
    }

    private function parseNRCCMessage(array $data): NRCCMessage
    {
        return new NRCCMessage($data['Value']);
    }
}
