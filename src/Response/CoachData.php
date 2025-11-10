<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class CoachData
{
    public function __construct(
        public string $coachClass,
        public string $number,
        public bool $loadingSpecified = false,
        public ?int $loading = null,
        public ?ToiletAvailabilityType $toilet = null,
    ) {}
}
