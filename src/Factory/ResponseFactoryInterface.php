<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Factory;

use HarmonicDigital\Ldbws\Exception\UnparseableResponseException;
use HarmonicDigital\Ldbws\Response\StationBoard;
use HarmonicDigital\Ldbws\Response\StationBoardWithDetails;

interface ResponseFactoryInterface
{
    /**
     * @throws UnparseableResponseException
     */
    public function parseStationBoard(array $data): StationBoard;
    public function parseStationBoardWithDetails(array $data): StationBoardWithDetails;
}
