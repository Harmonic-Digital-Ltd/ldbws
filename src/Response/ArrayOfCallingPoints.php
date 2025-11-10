<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class ArrayOfCallingPoints
{
    public function __construct(
        /** @var list<CallingPoint> */
        public array $callingPoint = [],
    ) {}
}
