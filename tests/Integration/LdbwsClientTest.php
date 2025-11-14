<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Tests\Integration;

use HarmonicDigital\Ldbws\Factory\ResponseFactory;
use HarmonicDigital\Ldbws\LdbwsClient;
use HarmonicDigital\Ldbws\Response\ArrayOfCallingPoints;
use HarmonicDigital\Ldbws\Response\CallingPoint;
use HarmonicDigital\Ldbws\Response\CoachData;
use HarmonicDigital\Ldbws\Response\FormationData;
use HarmonicDigital\Ldbws\Response\NRCCMessage;
use HarmonicDigital\Ldbws\Response\ServiceItem;
use HarmonicDigital\Ldbws\Response\ServiceItemWithCallingPoints;
use HarmonicDigital\Ldbws\Response\ServiceLocation;
use HarmonicDigital\Ldbws\Response\StationBoard;
use HarmonicDigital\Ldbws\Response\StationBoardWithDetails;
use HarmonicDigital\Ldbws\Response\ToiletAvailabilityType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LdbwsClient::class)]
#[UsesClass(ResponseFactory::class)]
#[UsesClass(CoachData::class)]
#[UsesClass(FormationData::class)]
#[UsesClass(ServiceItem::class)]
#[UsesClass(ServiceLocation::class)]
#[UsesClass(StationBoard::class)]
#[UsesClass(ToiletAvailabilityType::class)]
#[UsesClass(NRCCMessage::class)]
#[UsesClass(ArrayOfCallingPoints::class)]
#[UsesClass(CallingPoint::class)]
#[UsesClass(ServiceItemWithCallingPoints::class)]
#[UsesClass(StationBoardWithDetails::class)]
final class LdbwsClientTest extends TestCase
{
    private LdbwsClient $client;

    protected function setUp(): void
    {
        $apiKey = getenv('LDBWS_API_KEY');
        if (!$apiKey || empty($apiKey)) {
            $this->markTestSkipped('LDBWS_API_KEY environment variable is not set.');
        }
        $this->client = new LdbwsClient(
            $apiKey,
        );
    }

    #[DataProvider('getDepartureBoardProvider')]
    public function testGetDepartureBoard(string $crs): void
    {
        $stationBoard = $this->client->getDepartureBoard($crs);
        $this->assertSame($crs, $stationBoard->getCrs());
    }

    #[DataProvider('getDepartureBoardProvider')]
    public function testGetDepartureBoardWithDetails(string $crs): void
    {
        $stationBoard = $this->client->getDepartureBoardWithDetails($crs);
        $this->assertSame($crs, $stationBoard->getCrs());
    }

    public static function getDepartureBoardProvider(): iterable
    {
        yield 'Stockport' => ['SPT'];

        yield 'Manchester Piccadilly' => ['MAN'];

        yield 'London Euston' => ['EUS'];
    }
}
