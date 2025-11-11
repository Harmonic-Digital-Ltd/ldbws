<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Response;

enum ToiletStatus: string
{
    case UNKNOWN = 'Unknown';
    case INSERVICE = 'InService';
    case NOTINSERVICE = 'NotInService';
}
