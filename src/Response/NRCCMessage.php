<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

final readonly class NRCCMessage implements \Stringable
{
    public function __construct(
        public string $value,
    ) {}

    #[\Override]
    public function __toString(): string
    {
        return $this->value;
    }
}
