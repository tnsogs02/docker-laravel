<?php

namespace App\Services;

use stdClass;

class OrderService
{
    public function convert(array $sanitizedOrder)
    {
        // 待確認：需求方未明示price>2000檢查置於USD轉TWD前或後
        // 待確認：price下限, price轉換後溢位疑慮

        $returnArray = [
            "error" => true,
            "message" => "",
            "arrayOrder" => [],
        ];

        if(preg_match('/[^A-Za-z ]/', $sanitizedOrder['name'], $matches) === 1){
            $returnArray['message'] = "Name contains non-English characters";
            return $returnArray;
        }

        foreach(explode(' ', $sanitizedOrder['name']) as $namePiece){
            if(!ctype_upper($namePiece[0])){
                $returnArray['message'] = "Name is not capitalized";
                return $returnArray;
            }
        }

        if(!in_array($sanitizedOrder['currency'], ["TWD", "USD"])){
            $returnArray['message'] = "Currency format is wrong";
            return $returnArray;
        }

        if($sanitizedOrder['currency'] == "USD"){
            $sanitizedOrder['currency'] = "TWD";
            $sanitizedOrder['price'] = $sanitizedOrder['price'] * 31;
        }

        if($sanitizedOrder['price'] > 2000){
            $returnArray['message'] = "Price is over 2000";
            return $returnArray;
        }

        $returnArray['error'] = false;
        $returnArray['arrayOrder'] = $sanitizedOrder;

        return $returnArray;
    }
}
