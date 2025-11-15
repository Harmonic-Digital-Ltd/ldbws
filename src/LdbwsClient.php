<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use HarmonicDigital\Ldbws\Exception\LdbwsFaultException;
use HarmonicDigital\Ldbws\Exception\LdbwsUnknownException;
use HarmonicDigital\Ldbws\Exception\UnparseableResponseException;
use HarmonicDigital\Ldbws\Factory\ResponseFactory;
use HarmonicDigital\Ldbws\Factory\ResponseFactoryInterface;
use HarmonicDigital\Ldbws\Response\FilterType;
use HarmonicDigital\Ldbws\Response\StationBoard;
use HarmonicDigital\Ldbws\Response\StationBoardWithDetails;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final readonly class LdbwsClient implements LdbwsClientInterface
{
    private const string BASE_URL = 'https://api1.raildata.org.uk/1010-live-departure-board-dep1_2/LDBWS/api/20220120';

    public function __construct(
        private string $apiKey,
        private ClientInterface $client = new Client(),
        private LoggerInterface $logger = new NullLogger(),
        private ResponseFactoryInterface $responseFactory = new ResponseFactory(),
    ) {}

    /**
     * @param null|int<0, 150>    $numRows
     * @param null|int<-120, 120> $timeOffset
     * @param null|int<-120, 120> $timeWindow
     *
     * @throws UnparseableResponseException
     * @throws LdbwsFaultException
     * @throws LdbwsUnknownException
     */
    #[\Override]
    public function getDepartureBoard(
        string $crs,
        ?int $numRows = null,
        ?string $filterCrs = null,
        ?FilterType $filterType = null,
        ?int $timeOffset = null,
        ?int $timeWindow = null,
    ): StationBoard {
        $url = self::BASE_URL.'/GetDepartureBoard/'.$crs;

        try {
            $data = $this->makeRequest($url, [
                'numRows' => $numRows,
                'filterCrs' => $filterCrs,
                'filterType' => $filterType?->value,
                'timeOffset' => $timeOffset,
                'timeWindow' => $timeWindow,
            ]);
        } catch (\JsonException $e) {
            throw new UnparseableResponseException(StationBoard::class, $e->getCode(), $e);
        }

        return $this->responseFactory->parseStationBoard($data);
    }

    /**
     * @param null|int<0, 150> $numRows
     * @param null|int<-120, 120> $timeOffset
     * @param null|int<-120, 120> $timeWindow
     *
     * @throws UnparseableResponseException
     * @throws LdbwsFaultException
     * @throws LdbwsUnknownException
     */
    #[\Override]
    public function getDepartureBoardWithDetails(
        string $crs,
        ?int $numRows = null,
        ?string $filterCrs = null,
        ?FilterType $filterType = null,
        ?int $timeOffset = null,
        ?int $timeWindow = null,
    ): StationBoardWithDetails {
        $url = self::BASE_URL.'/GetDepBoardWithDetails/'.$crs;

        try {
            $data = $this->makeRequest($url, [
                'numRows' => $numRows,
                'filterCrs' => $filterCrs,
                'filterType' => $filterType?->value,
                'timeOffset' => $timeOffset,
                'timeWindow' => $timeWindow,
            ]);
        } catch (\JsonException $e) {
            throw new UnparseableResponseException(StationBoardWithDetails::class, $e->getCode(), $e);
        }

        return $this->responseFactory->parseStationBoardWithDetails($data);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws LdbwsFaultException
     * @throws LdbwsUnknownException
     * @throws \JsonException
     */
    private function makeRequest(string $uri, array $queryParams = [], string $method = 'GET'): array
    {
        $queryParams = array_filter($queryParams);
        $context = [
            'http_method' => $method,
            'url' => $uri,
            'query_params' => $queryParams,
        ];
        $this->logger->debug('-> '.$uri, $context);

        try {
            $response = $this->client->request($method, $uri, [
                'headers' => [
                    'x-apikey' => $this->apiKey,
                    'accept' => 'application/json',
                ],
                'query' => $queryParams,
            ]);
        } catch (\Throwable $e) {
            $context['exception'] = $e;
            $context['exception_class'] = $e::class;
            $context['exception_message'] = $e->getMessage();
            if ($e instanceof RequestException) {
                $response = $e->getResponse();
                if (null !== $response) {
                    $respBody = (string) $response->getBody();
                    $context['body'] = $respBody;
                    $context['status_code'] = $response->getStatusCode();
                    $context['exception'] = $e;

                    try {
                        /** @var array<string, mixed> $decoded */
                        $decoded = \json_decode($respBody, true, 512, \JSON_THROW_ON_ERROR);
                        if (\is_array($decoded)
                            && isset($decoded['fault'])
                            && \is_array($decoded['fault'])
                        ) {
                            // Fault structure present
                            throw new LdbwsFaultException(
                                (string) $decoded['fault']['faultstring'],
                                (string) ($decoded['fault']['detail']['errorcode'] ?? ''),
                                $e,
                            );
                        }
                    } catch (\JsonException) {
                    }
                }
            }

            $this->logger->error('<- '.$uri, $context);

            throw new LdbwsUnknownException($e->getMessage(), (int) $e->getCode(), $e);
        }

        $body = $response->getBody()->getContents();
        $context['body'] = $body;
        $context['status_code'] = $response->getStatusCode();
        $this->logger->debug('<- '.$uri, $context);

        /** @var array<string, mixed> */
        return \json_decode($body, true, 512, \JSON_THROW_ON_ERROR);
    }
}
