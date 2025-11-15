<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

enum FilterType: string
{
    case TO = 'to';
    case FROM = 'from';
}
