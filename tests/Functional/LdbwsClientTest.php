<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Tests\Functional;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
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
use PHPUnit\Framework\MockObject\MockObject;
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
    private ClientInterface&MockObject $guzzle;

    protected function setUp(): void
    {
        $this->guzzle = $this->createMock(ClientInterface::class);
        $this->client = new LdbwsClient(
            'api-key',
            $this->guzzle,
        );
    }

    #[DataProvider('getDepartureBoardProvider')]
    public function testGetDepartureBoard($crs, $response): void
    {
        $this->guzzle->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'https://api1.raildata.org.uk/1010-live-departure-board-dep1_2/LDBWS/api/20220120/GetDepartureBoard/'.$crs,
                ['headers' => ['x-apikey' => 'api-key', 'accept' => 'application/json'], 'query' => []]
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], $response))
        ;

        $this->client->getDepartureBoard($crs);
    }

    public static function getDepartureBoardProvider(): iterable
    {
        yield 'Stockport' => [
            'SPT',
            file_get_contents(__DIR__.'/../Fixtures/Response/getDepartureBoard/SPT.json'),
        ];

        yield 'London' => [
            'EUS',
            file_get_contents(__DIR__.'/../Fixtures/Response/getDepartureBoard/EUS.json'),
        ];
    }

    #[DataProvider('getDepartureBoardWithDetailsProvider')]
    public function testGetDepartureBoardWithDetails($crs, $response): void
    {
        $this->guzzle->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'https://api1.raildata.org.uk/1010-live-departure-board-dep1_2/LDBWS/api/20220120/GetDepBoardWithDetails/'.$crs,
                ['headers' => ['x-apikey' => 'api-key', 'accept' => 'application/json'], 'query' => []]
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], $response))
        ;

        $this->client->getDepartureBoardWithDetails($crs);
    }

    public static function getDepartureBoardWithDetailsProvider(): iterable
    {
        yield 'Stockport' => [
            'SPT',
            file_get_contents(__DIR__.'/../Fixtures/Response/getDepBoardWithDetails/SPT.json'),
        ];
    }
}
