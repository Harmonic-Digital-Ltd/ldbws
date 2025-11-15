# Live Departure Board Web Service (LDBWS) SDK

A lightweight PHP 8.3+ client for the National Rail UK Live Departure Board Web Service (LDBWS). 

This library wraps the official REST endpoints exposed via Rail Data Marketplace and provides typed responses for station boards. It uses Guzzle under the hood and follows PSR-3 for logging.

- Package name: `harmonicdigital/ldbws`
- Minimum PHP: 8.3
- Transport: Guzzle (PSR-18 compatible via `guzzlehttp/guzzle`), PSR-3 logging

## Installation

Install via Composer:

```
composer require harmonicdigital/ldbws
```

You will need an API key for the Rail Data Marketplace (LDBWS). Supply it when constructing the client.

## Features

Currently implemented service methods in `HarmonicDigital\Ldbws\LdbwsClient`:

1) `getDepartureBoard()` — Fetch a basic departure board for a station.

Example:

```php
<?php

use HarmonicDigital\Ldbws\LdbwsClient;
use HarmonicDigital\Ldbws\Response\FilterType;

$client = new LdbwsClient(apiKey: 'YOUR_API_KEY');

// Get the next 5 departures from London Euston (EUS) heading to Watford Junction (WFJ)
$board = $client->getDepartureBoard(
    'EUS',            // CRS code for the station
    5,            // up to 150
    'WFJ',      // optional filter station CRS
    FilterType::TO, // or FilterType::From
    0,         // minutes before/after current time (-120 to 120)
    60,        // window of results in minutes (0 to 120)
);

// $board is an instance of HarmonicDigital\Ldbws\Response\StationBoard
// Iterate services, times, destinations, etc.
```

2) `getDepartureBoardWithDetails()` — Fetch a departure board including calling point details for services.

Example:

```php
<?php

use HarmonicDigital\Ldbws\LdbwsClient;
use HarmonicDigital\Ldbws\Response\FilterType;

$client = new LdbwsClient(apiKey: 'YOUR_API_KEY');

$boardWithDetails = $client->getDepartureBoardWithDetails('EUS');

// $boardWithDetails is an instance of HarmonicDigital\Ldbws\Response\StationBoardWithDetails
// It includes additional detail per service (e.g., calling points)
```

All methods may throw:

- `HarmonicDigital\Ldbws\Exception\LdbwsFaultException` when the API returns a known LDBWS fault
- `HarmonicDigital\Ldbws\Exception\LdbwsUnknownException` for unexpected transport or server errors
- `HarmonicDigital\Ldbws\Exception\UnparseableResponseException` when the response cannot be parsed

## To Do

The following endpoints are present in the official swagger (`config/ldbws-swagger.json`) but are not yet implemented in `LdbwsClient`:

- GetArrivalBoard
- GetArrivalDepartureBoard
- GetArrBoardWithDetails
- GetArrDepBoardWithDetails
- GetFastestDepartures
- GetFastestDeparturesWithDetails
- GetNextDepartures
- GetNextDeparturesWithDetails
- GetServiceDetails

Implemented already:

- GetDepartureBoard
- GetDepBoardWithDetails (exposed as `getDepartureBoardWithDetails`)