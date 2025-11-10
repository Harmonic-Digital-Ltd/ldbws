<?php
declare(strict_types=1);


namespace HarmonicDigital\Ldbws\Response;

interface Station
{
    public function getName(): string;
    public function getCrs(): string;
}
