<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use HarmonicDigital\Ldbws\Exception\UnparseableResponseException;
use HarmonicDigital\Ldbws\Factory\ResponseFactory;
use HarmonicDigital\Ldbws\Factory\ResponseFactoryInterface;
use HarmonicDigital\Ldbws\Response\FilterType;
use HarmonicDigital\Ldbws\Response\StationBoard;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final readonly class LdbwsClient
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
     */
    public function getDepartureBoard(
        string $crs,
        ?int $numRows = null,
        ?string $filterCrs = null,
        ?FilterType $filterType = null,
        ?int $timeOffset = null,
        ?int $timeWindow = null,
    ): StationBoard {
        $url = self::BASE_URL.'/GetDepartureBoard/'.$crs;
        $data = $this->makeRequest($url, [
            'numRows' => $numRows,
            'filterCrs' => $filterCrs,
            'filterType' => $filterType?->value,
            'timeOffset' => $timeOffset,
            'timeWindow' => $timeWindow,
        ]);

        return $this->responseFactory->parseStationBoard($data);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws GuzzleException
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
        $response = $this->client->request($method, $uri, [
            'headers' => [
                'x-apikey' => $this->apiKey,
                'accept' => 'application/json',
            ],
            'query' => $queryParams,
        ]);
        $body = $response->getBody()->getContents();
        $context['body'] = $body;
        $context['status_code'] = $response->getStatusCode();
        $this->logger->debug('<- '.$uri, $context);

        // @var array<string, mixed>
        return \json_decode($body, true, 512, \JSON_THROW_ON_ERROR);
    }
}
