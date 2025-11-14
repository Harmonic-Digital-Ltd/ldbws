<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

enum UncertaintyTypeStatus: string
{
    case DELAY = 'Delay';
    case CANCELLATION = 'Cancellation';
    case OTHER = 'Other';
}
