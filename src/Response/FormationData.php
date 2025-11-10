<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class FormationData
{
    public function __construct(
        /** @var list<CoachData> */
        public array $coaches = [],
    ) {}
}
