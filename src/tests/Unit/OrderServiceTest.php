<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\OrderService;

class OrderServiceTest extends TestCase
{
    // VALID SAMPLE
    //
    // [
    //     [
    //         "id" => "A0000001",
    //         "name" => "Melody Holiday Inn",
    //         "address" => [
    //             "city" => "taipei-city",
    //             "district" => "da-an-district",
    //             "street" => "fuxing-south-road"
    //         ],
    //         "price" => "10",
    //         "currency" => "USD"
    //     ],
    //     [
    //         "error" => false,
    //         "message" => "",
    //     ]
    // ]



    /**
     * @dataProvider invalidData
     */
    public function testConvertInvalid(array $sanitizedOrder,array $expected): void
    {
        $orderService = new OrderService();

        $output = $orderService->convert($sanitizedOrder);
        $this->assertEquals($expected['error'], $output['error']);
        $this->assertEquals($expected['message'], $output['message']);
    }

    /**
     * @dataProvider validData
     */
    public function testConvertValid(array $sanitizedOrder,array $expected): void
    {
        $orderService = new OrderService();

        $output = $orderService->convert($sanitizedOrder);
        $this->assertEquals($expected['error'], $output['error']);
        $this->assertEquals($expected['message'], $output['message']);
        $this->assertEquals($expected['arrayOrder'], $output['arrayOrder']);
    }

    public static function invalidData(): array
    {
        return [
            // Case: Name contains non-English characters
            [
                [
                    "id" => "A0000001",
                    "name" => "測Melody Holiday Inn",
                    "address" => [
                        "city" => "taipei-city",
                        "district" => "da-an-district",
                        "street" => "fuxing-south-road"
                    ],
                    "price" => "60",
                    "currency" => "USD"
                ],
                [
                    "error" => true,
                    "message" => "Name contains non-English characters",
                ]
            ],
            // Case: Name is not capitalized
            [
                [
                    "id" => "A0000001",
                    "name" => "melody Holiday Inn",
                    "address" => [
                        "city" => "taipei-city",
                        "district" => "da-an-district",
                        "street" => "fuxing-south-road"
                    ],
                    "price" => "60",
                    "currency" => "USD"
                ],
                [
                    "error" => true,
                    "message" => "Name is not capitalized",
                ]
            ],
            [
                [
                    "id" => "A0000001",
                    "name" => "Melody Holiday inn",
                    "address" => [
                        "city" => "taipei-city",
                        "district" => "da-an-district",
                        "street" => "fuxing-south-road"
                    ],
                    "price" => "60",
                    "currency" => "USD"
                ],
                [
                    "error" => true,
                    "message" => "Name is not capitalized",
                ]
            ],
            [
                [
                    "id" => "A0000001",
                    "name" => "i",
                    "address" => [
                        "city" => "taipei-city",
                        "district" => "da-an-district",
                        "street" => "fuxing-south-road"
                    ],
                    "price" => "60",
                    "currency" => "USD"
                ],
                [
                    "error" => true,
                    "message" => "Name is not capitalized",
                ]
            ],
            // Case: Price is over 2000
            [
                [
                    "id" => "A0000001",
                    "name" => "Melody Holiday Inn",
                    "address" => [
                        "city" => "taipei-city",
                        "district" => "da-an-district",
                        "street" => "fuxing-south-road"
                    ],
                    "price" => "2100",
                    "currency" => "TWD"
                ],
                [
                    "error" => true,
                    "message" => "Price is over 2000",
                ]
            ],
            // Case: Price is over 2000(USD to TWD)
            [
                [
                    "id" => "A0000001",
                    "name" => "Melody Holiday Inn",
                    "address" => [
                        "city" => "taipei-city",
                        "district" => "da-an-district",
                        "street" => "fuxing-south-road"
                    ],
                    "price" => "70",
                    "currency" => "USD"
                ],
                [
                    "error" => true,
                    "message" => "Price is over 2000",
                ]
            ],
            // Case: Currency format is wrong
            [
                [
                    "id" => "A0000001",
                    "name" => "Melody Holiday Inn",
                    "address" => [
                        "city" => "taipei-city",
                        "district" => "da-an-district",
                        "street" => "fuxing-south-road"
                    ],
                    "price" => "70",
                    "currency" => "JPY"
                ],
                [
                    "error" => true,
                    "message" => "Currency format is wrong",
                ]
            ],
        ];
    }

    public static function validData(): array
    {
        return [
            // Case: Valid input with Currency=TWD
            [
                [
                    "id" => "A0000001",
                    "name" => "Melody Holiday Inn",
                    "address" => [
                        "city" => "taipei-city",
                        "district" => "da-an-district",
                        "street" => "fuxing-south-road"
                    ],
                    "price" => "100",
                    "currency" => "TWD"
                ],
                [
                    "error" => false,
                    "message" => "",
                    "arrayOrder" => [
                        "id" => "A0000001",
                        "name" => "Melody Holiday Inn",
                        "address" => [
                            "city" => "taipei-city",
                            "district" => "da-an-district",
                            "street" => "fuxing-south-road"
                        ],
                        "price" => "100",
                        "currency" => "TWD"
                    ]
                ]
            ],
            // Case: Valid input with Currency=USD and converted to TWD
            [
                [
                    "id" => "A0000001",
                    "name" => "Melody Holiday Inn",
                    "address" => [
                        "city" => "taipei-city",
                        "district" => "da-an-district",
                        "street" => "fuxing-south-road"
                    ],
                    "price" => "10",
                    "currency" => "USD"
                ],
                [
                    "error" => false,
                    "message" => "",
                    "arrayOrder" => [
                        "id" => "A0000001",
                        "name" => "Melody Holiday Inn",
                        "address" => [
                            "city" => "taipei-city",
                            "district" => "da-an-district",
                            "street" => "fuxing-south-road"
                        ],
                        "price" => "310",
                        "currency" => "TWD"
                    ]
                ]
            ],
        ];
    }
}
