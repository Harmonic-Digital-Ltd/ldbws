<?php
declare(strict_types=1);

namespace HarmonicDigital\Ldbws;

use HarmonicDigital\Ldbws\Exception\LdbwsFaultException;
use HarmonicDigital\Ldbws\Exception\LdbwsUnknownException;
use HarmonicDigital\Ldbws\Exception\UnparseableResponseException;
use HarmonicDigital\Ldbws\Response\FilterType;
use HarmonicDigital\Ldbws\Response\StationBoard;
use HarmonicDigital\Ldbws\Response\StationBoardWithDetails;

interface LdbwsClientInterface
{
    /**
     * @param null|int<0, 150> $numRows
     * @param null|int<-120, 120> $timeOffset
     * @param null|int<-120, 120> $timeWindow
     *
     * @throws LdbwsFaultException
     * @throws LdbwsUnknownException
     * @throws UnparseableResponseException
     */
    public function getDepartureBoard(
        string $crs,
        ?int $numRows = null,
        ?string $filterCrs = null,
        ?FilterType $filterType = null,
        ?int $timeOffset = null,
        ?int $timeWindow = null,
    ): StationBoard;

    /**
     * @param null|int<0, 150> $numRows
     * @param null|int<-120, 120> $timeOffset
     * @param null|int<-120, 120> $timeWindow
     *
     * @throws LdbwsFaultException
     * @throws LdbwsUnknownException
     * @throws UnparseableResponseException
     */
    public function getDepartureBoardWithDetails(
        string $crs,
        ?int $numRows = null,
        ?string $filterCrs = null,
        ?FilterType $filterType = null,
        ?int $timeOffset = null,
        ?int $timeWindow = null,
    ): StationBoardWithDetails;
}