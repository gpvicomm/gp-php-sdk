<?php

use Vicomm\Exceptions\{RequestException};
use Vicomm\Vicomm;
use PHPUnit\Framework\TestCase;

final class VicommTest extends TestCase
{
    public function testInvalidResource()
    {
        $this->expectException(RequestException::class);

        Vicomm::init("random", "random");
        Vicomm::randomResource();
    }

}