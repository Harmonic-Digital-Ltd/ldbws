<?php

declare(strict_types=1);

namespace HarmonicDigital\Ldbws\Tests\Unit\Factory;

use HarmonicDigital\Ldbws\Factory\ResponseFactory;
use HarmonicDigital\Ldbws\Response\ArrayOfCallingPoints;
use HarmonicDigital\Ldbws\Response\BaseStationBoard;
use HarmonicDigital\Ldbws\Response\CallingPoint;
use HarmonicDigital\Ldbws\Response\CoachData;
use HarmonicDigital\Ldbws\Response\FilterType;
use HarmonicDigital\Ldbws\Response\FormationData;
use HarmonicDigital\Ldbws\Response\NRCCMessage;
use HarmonicDigital\Ldbws\Response\ServiceItem;
use HarmonicDigital\Ldbws\Response\ServiceItemWithCallingPoints;
use HarmonicDigital\Ldbws\Response\ServiceLocation;
use HarmonicDigital\Ldbws\Response\ServiceType;
use HarmonicDigital\Ldbws\Response\StationBoard;
use HarmonicDigital\Ldbws\Response\StationBoardWithDetails;
use HarmonicDigital\Ldbws\Response\ToiletAvailabilityType;
use HarmonicDigital\Ldbws\Response\ToiletStatus;
use HarmonicDigital\Ldbws\Response\ToiletType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ResponseFactory::class)]
#[CoversClass(CoachData::class)]
#[CoversClass(FormationData::class)]
#[CoversClass(ServiceItem::class)]
#[CoversClass(ServiceLocation::class)]
#[CoversClass(StationBoard::class)]
#[CoversClass(ToiletAvailabilityType::class)]
#[CoversClass(ArrayOfCallingPoints::class)]
#[CoversClass(BaseStationBoard::class)]
#[CoversClass(CallingPoint::class)]
#[CoversClass(NRCCMessage::class)]
#[CoversClass(ServiceItemWithCallingPoints::class)]
#[CoversClass(StationBoardWithDetails::class)]
final class ResponseFactoryTest extends TestCase
{
    private ResponseFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new ResponseFactory();
    }

    public function testParseDepartureBoardResponse(): void
    {
        $json = json_decode(
            file_get_contents(__DIR__.'/../../Fixtures/Response/getDepartureBoard/SPT.json'),
            true,
            flags: JSON_THROW_ON_ERROR
        );
        $board = $this->factory->parseStationBoard($json);

        $this->assertSame('Stockport', $board->getName());
        $this->assertSame('SPT', $board->getCrs());
        $this->assertSame(FilterType::To, $board->filterType);
        $this->assertTrue($board->platformAvailable);
        $this->assertTrue($board->areServicesAvailable);
        $this->assertEquals(
            new \DateTimeImmutable($json['generatedAt']),
            $board->generatedAt
        );
        // JSON contains an extra decimal place in the microseconds
        $this->assertStringStartsWith($board->generatedAt->format('Y-m-d\TH:i:s.u'), $json['generatedAt']);
        $this->assertStringEndsWith($board->generatedAt->format('P'), $json['generatedAt']);

        $this->assertCount(\count($json['trainServices']), $board->trainServices);

        foreach ($board->trainServices as $i => $service) {
            $this->assertInstanceOf(ServiceItem::class, $service);

            $this->assertSame($json['trainServices'][$i]['operator'], $service->operator);
            $this->assertSame($json['trainServices'][$i]['operatorCode'], $service->operatorCode);
            $this->assertSame($json['trainServices'][$i]['std'], $service->std);
            $this->assertSame($json['trainServices'][$i]['etd'], $service->etd);
            $this->assertSame(ServiceType::TRAIN, $service->serviceType);

            $this->assertNotEmpty($service->origin);
            $this->assertSame($json['trainServices'][$i]['origin'][0]['locationName'], $service->origin[0]->getName());
            $this->assertSame($json['trainServices'][$i]['origin'][0]['crs'], $service->origin[0]->getCrs());

            $this->assertNotEmpty($service->destination);
            $this->assertSame(
                $json['trainServices'][$i]['destination'][0]['locationName'],
                $service->destination[0]->getName()
            );
            $this->assertSame($json['trainServices'][$i]['destination'][0]['crs'], $service->destination[0]->getCrs());
        }

        $this->assertCount(1, $board->nrccMessages);
        $this->assertInstanceOf(NRCCMessage::class, $board->nrccMessages[0]);
    }

    public function testFormation(): void
    {
        $json = json_decode(
            file_get_contents(__DIR__.'/../../Fixtures/Response/getDepartureBoard/EUS.json'),
            true,
            flags: JSON_THROW_ON_ERROR
        );
        $board = $this->factory->parseStationBoard($json);
        $formation = $board->trainServices[4]->formation;
        $this->assertInstanceOf(FormationData::class, $formation);
        $this->assertCount(8, $formation->coaches);
        $this->assertSame('Standard', $formation->coaches[1]->coachClass);
        $this->assertSame('A2', $formation->coaches[1]->number);
        $this->assertSame(ToiletStatus::INSERVICE, $formation->coaches[1]->toilet->status);
        $this->assertSame(ToiletType::UNKNOWN, $formation->coaches[1]->toilet->value);
    }

    public function testParseDepartureBoardWithDetailsResponse(): void
    {
        $json = json_decode(
            file_get_contents(__DIR__.'/../../Fixtures/Response/getDepBoardWithDetails/SPT.json'),
            true,
            flags: JSON_THROW_ON_ERROR
        );
        $board = $this->factory->parseStationBoardWithDetails($json);

        $this->assertSame('Stockport', $board->getName());
        $this->assertSame('SPT', $board->getCrs());
        $this->assertSame(FilterType::To, $board->filterType);
        $this->assertTrue($board->platformAvailable);
        $this->assertTrue($board->areServicesAvailable);
        $this->assertEquals(
            new \DateTimeImmutable($json['generatedAt']),
            $board->generatedAt
        );
        // JSON contains an extra decimal place in the microseconds
        $this->assertStringStartsWith($board->generatedAt->format('Y-m-d\TH:i:s.u'), $json['generatedAt']);
        $this->assertStringEndsWith($board->generatedAt->format('P'), $json['generatedAt']);

        $this->assertCount(\count($json['trainServices']), $board->trainServices);

        foreach ($board->trainServices as $i => $service) {
            $this->assertInstanceOf(ServiceItemWithCallingPoints::class, $service);

            $this->assertSame($json['trainServices'][$i]['operator'], $service->operator);
            $this->assertSame($json['trainServices'][$i]['operatorCode'], $service->operatorCode);
            $this->assertSame($json['trainServices'][$i]['std'], $service->std);
            $this->assertSame($json['trainServices'][$i]['etd'], $service->etd);
            $this->assertSame(ServiceType::TRAIN, $service->serviceType);

            $this->assertNotEmpty($service->origin);
            $this->assertSame($json['trainServices'][$i]['origin'][0]['locationName'], $service->origin[0]->getName());
            $this->assertSame($json['trainServices'][$i]['origin'][0]['crs'], $service->origin[0]->getCrs());

            $this->assertNotEmpty($service->destination);
            $this->assertSame(
                $json['trainServices'][$i]['destination'][0]['locationName'],
                $service->destination[0]->getName()
            );
            $this->assertSame($json['trainServices'][$i]['destination'][0]['crs'], $service->destination[0]->getCrs());
        }
        $callingPoints = $board->trainServices[0]->subsequentCallingPoints;
        $this->assertCount(1, $callingPoints);
        $this->assertSame(ServiceType::TRAIN, $callingPoints[0]->serviceType);
        $this->assertFalse($callingPoints[0]->serviceChangeRequired);
        $this->assertFalse($callingPoints[0]->assocIsCancelled);
        $this->assertCount(1, $callingPoints[0]->callingPoint);
        $callingPoint = $callingPoints[0]->callingPoint[0];
        $this->assertSame('MAN', $callingPoint->getCrs());
        $this->assertSame('Manchester Piccadilly', $callingPoint->getName());
        $this->assertSame('15:19', $callingPoint->st);
        $this->assertCount(1, $board->nrccMessages);
        $this->assertInstanceOf(NRCCMessage::class, $board->nrccMessages[0]);
    }
}
