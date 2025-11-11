<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class CallingPoint implements Station
{
    public function __construct(
        public string $locationName,
        public string $crs,
        public ?string $st = null,
        public ?string $et = null,
        public ?string $at = null,
        public bool $isCancelled = false,
        public int $length = 0,
        public bool $detachFront = false,
        public ?FormationData $formation = null,
        public bool $affectedByDiversion = false,
        public int $rerouteDelay = 0,
        /** @var list<string> */
        public array $adhocAlerts = [],
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
