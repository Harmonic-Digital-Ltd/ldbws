<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class UncertaintyType
{
    public function __construct(
        public UncertaintyTypeStatus $status = UncertaintyTypeStatus::OTHER,
        public string $reason = '',
    ) {}
}
