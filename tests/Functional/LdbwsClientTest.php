<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Tests\Functional;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HarmonicDigital\Ldbws\Exception\LdbwsFaultException;
use HarmonicDigital\Ldbws\Exception\LdbwsUnknownException;
use HarmonicDigital\Ldbws\Exception\UnparseableResponseException;
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
#[CoversClass(LdbwsFaultException::class)]
#[CoversClass(LdbwsUnknownException::class)]
#[CoversClass(UnparseableResponseException::class)]
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

    public function testThrowsLdbwsFaultExceptionWhenGuzzleResponseContainsFaultIncorrectToken(): void
    {
        $body = file_get_contents(__DIR__.'/../Fixtures/Response/Fault/incorrect_api_token.json');
        $requestException = new RequestException(
            'Unauthorized',
            new Request('GET', 'https://example.test'),
            new Response(401, ['Content-Type' => 'application/json'], $body),
        );
        $this->guzzle->expects($this->once())
            ->method('request')
            ->willThrowException($requestException)
        ;

        $this->expectException(LdbwsFaultException::class);

        try {
            $this->client->getDepartureBoard('AAA');
        } catch (LdbwsFaultException $e) {
            $this->assertSame('Invalid ApiKey', $e->getMessage());
            $this->assertSame('oauth.v2.InvalidApiKey', $e->getFaultCode());
            $this->assertSame($requestException, $e->getPrevious());
            $this->assertSame('Invalid ApiKey', $e->getFault());

            throw $e;
        }
    }

    public function testThrowsLdbwsFaultExceptionWhenGuzzleResponseContainsFaultNoApiKey(): void
    {
        $body = file_get_contents(__DIR__.'/../Fixtures/Response/Fault/no_api_key.json');
        $requestException = new RequestException(
            'Unauthorized',
            new Request('GET', 'https://example.test'),
            new Response(401, ['Content-Type' => 'application/json'], $body),
        );
        $this->guzzle->expects($this->once())
            ->method('request')
            ->willThrowException($requestException)
        ;

        $this->expectException(LdbwsFaultException::class);

        try {
            $this->client->getDepartureBoard('AAA');
        } catch (LdbwsFaultException $e) {
            $this->assertSame('Failed to resolve API Key variable request.header.x-apikey', $e->getMessage());
            $this->assertSame('steps.oauth.v2.FailedToResolveAPIKey', $e->getFaultCode());
            $this->assertSame($requestException, $e->getPrevious());
            $this->assertSame('Failed to resolve API Key variable request.header.x-apikey', $e->getFault());

            throw $e;
        }
    }

    public function testThrowsUnparseableResponseExceptionOnInvalidJsonBody(): void
    {
        $this->guzzle->expects($this->once())
            ->method('request')
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], 'not-json'))
        ;

        $this->expectException(UnparseableResponseException::class);

        try {
            $this->client->getDepartureBoard('AAA');
        } catch (UnparseableResponseException $e) {
            $this->assertSame(StationBoard::class, $e->getClass());
            $this->assertSame(
                'Unable to parse class "HarmonicDigital\Ldbws\Response\StationBoard": Syntax error',
                $e->getMessage()
            );
            $this->assertInstanceOf(\JsonException::class, $e->getPrevious());

            throw $e;
        }
    }

    public function testThrowsLdbwsUnknownExceptionOnRequestExceptionWithoutResponse(): void
    {
        $requestException = new RequestException(
            'Network error',
            new Request('GET', 'https://example.test'),
            null,
        );
        $this->guzzle->expects($this->once())
            ->method('request')
            ->willThrowException($requestException)
        ;

        $this->expectException(LdbwsUnknownException::class);
        $this->expectExceptionMessage('Network error');
        $this->expectExceptionCode(0);

        try {
            $this->client->getDepartureBoard('AAA');
        } catch (LdbwsUnknownException $e) {
            $this->assertSame($requestException, $e->getPrevious());

            throw $e;
        }
    }
}
