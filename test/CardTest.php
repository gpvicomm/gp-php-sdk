<?php

use Vicomm\Exceptions\{VicommErrorException, RequestException};
use Vicomm\Vicomm;
use PHPUnit\Framework\TestCase;

final class CardTest extends TestCase
{
    protected $service;

    public function setUp()
    {
        Vicomm::init("MAGENTO_MX_SERVER", "DKzCAv6EXXgQrC0hATOltXZ6OZ7Zss");
        $this->service = Vicomm::card();
    }

    public function testSuccessCardList()
    {
        $list = $this->service->getList(1);
        $this->assertIsObject($list);
        $this->assertTrue(($list instanceof \stdClass));
        $this->assertIsNumeric($list->result_size);
        $this->assertIsArray($list->cards);
    }

    public function testFailParamsCardList()
    {
        $this->expectException(RequestException::class);
        $this->service->getList("randomUID");
    }

    public function testFailCardList()
    {
        $this->expectException(VicommErrorException::class);
        Vicomm::init("1", "s");
        $service = Vicomm::card();
        $service->getList("1");
    }
}