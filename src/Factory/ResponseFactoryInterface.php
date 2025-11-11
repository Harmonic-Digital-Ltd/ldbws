<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Factory;

use HarmonicDigital\Ldbws\Exception\UnparseableResponseException;
use HarmonicDigital\Ldbws\Response\StationBoard;

interface ResponseFactoryInterface
{
    /**
     * @throws UnparseableResponseException
     */
    public function parseStationBoard(array $data): StationBoard;
}
