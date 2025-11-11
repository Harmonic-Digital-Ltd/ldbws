<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class FormationData
{
    public function __construct(
        public ?LoadingCategory $loadingCategory = null,
        /** @var list<CoachData> */
        public array $coaches = [],
    ) {}
}
