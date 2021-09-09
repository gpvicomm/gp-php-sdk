<?php

namespace Vicomm;

use Vicomm\Resources\{Card, Cash, Charge};

class Settings
{
    const DEFAULT_SECONDS_TIMEOUT = 90;
    const CCAPI = 'ccapi';
    const NOCCAPI = 'noccapi';
    const API_VERSION = "v2";

    const BASE_URL = [
        self::CCAPI => [
            'production' => "https://ccapi.gpvicomm.com",
            'staging' => "https://ccapi-stg.gpvicomm.com"
        ],
        self::NOCCAPI => [
            'production' => "https://noccapi.gpvicomm.com",
            'staging' => "https://noccapi-stg.gpvicomm.com"
        ]
    ];

    const API_RESOURCES = [
        'card' => [
            'class' => Card::class,
            'api' => self::CCAPI
        ],
        'cash' => [
            'class' => Cash::class,
            'api' => self::NOCCAPI
        ],
        'charge' => [
            'class' => Charge::class,
            'api' => self::CCAPI
        ]
    ];

    const DEFAULT_HEADERS = [
        'Content-Type' => "application/json"
    ];
}