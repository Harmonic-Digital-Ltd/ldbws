<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class CoachData
{
    public function __construct(
        /** @var null|'First'|'Mixed'|'Standard'|string */
        public ?string $coachClass = null,
        public ?string $number = null,
        public bool $loadingSpecified = false,
        /** @var null|int<0, 100> */
        public ?int $loading = null,
        public ?ToiletAvailabilityType $toilet = null,
    ) {}
}
