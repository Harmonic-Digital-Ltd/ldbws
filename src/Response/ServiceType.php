<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

enum ServiceType: string
{
    case TRAIN = 'train';
    case BUS = 'bus';
    case FERRY = 'ferry';
}
