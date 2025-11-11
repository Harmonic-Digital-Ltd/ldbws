<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Tests\Integration;

use HarmonicDigital\Ldbws\LdbwsClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LdbwsClient::class)]
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
    public function testGetDepartureBoard($crs): void
    {
        $stationBoard = $this->client->getDepartureBoard($crs);
        $this->assertSame($crs, $stationBoard->getCrs());
    }

    public static function getDepartureBoardProvider(): iterable
    {
        yield 'Stockport' => ['SPT'];

        yield 'Manchester Piccadilly' => ['MAN'];

        yield 'London Euston' => ['EUS'];
    }
}
