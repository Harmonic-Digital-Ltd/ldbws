<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class ServiceItemWithCallingPoints
{
    public function __construct(
        public string $serviceID,
        /** @var list<ServiceLocation> */
        public array $origin = [],
        /** @var list<ServiceLocation> */
        public array $destination = [],
        /** @var list<ArrayOfCallingPoints> */
        public array $subsequentCallingPoints = [],
        public bool $futureCancellation = false,
        public bool $futureDelay = false,
        public ?\DateTimeImmutable $std = null,
        public ?\DateTimeImmutable $etd = null,
        public ?string $platform = null,
        public ?string $operator = null,
        public ?string $operatorCode = null,
        public bool $isCircularRoute = false,
        public bool $isCancelled = false,
        public bool $filterLocationCancelled = false,
        public ?string $serviceType = null,
        public int $length = 0,
        public bool $detachFront = false,
        public bool $isReverseFormation = false,
    ) {}
}
