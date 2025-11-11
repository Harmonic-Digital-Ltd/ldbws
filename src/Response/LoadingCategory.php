<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class LoadingCategory
{
    public function __construct(
        public ?string $code = null,
        public ?string $colour = null,
        public ?string $image = null,
        public ?string $value = null,
    ) {}
}
