<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Tests\Functional;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use HarmonicDigital\Ldbws\LdbwsClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LdbwsClient::class)]
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
}
