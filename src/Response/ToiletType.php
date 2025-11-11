<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

enum ToiletType: string
{
    case UNKNOWN = 'Unknown';
    case NONE = 'None';
    case STANDARD = 'Standard';
    case ACCESSIBLE = 'Accessible';
}
