<?php

namespace App\Services;

use stdClass;

class OrderService
{
    public function convert(array $validatedOrder)
    {
        // 待確認：需求方未明示price>2000檢查置於USD轉TWD前或後
        // 待確認：price下限, price轉換後溢位疑慮

        $returnObj = new stdClass;
        $returnObj->error = true;
        $returnObj->message = "";
        $returnObj->arrayOrder = array();

        if(preg_match('/[^A-Za-z ]/', $validatedOrder['name'], $matches) === 1){
            $returnObj->message = "Name contains non-English characters";
            return $returnObj;
        }

        foreach(explode(' ', $validatedOrder['name']) as $namePiece){
            if(!ctype_upper($namePiece[0])){
                $returnObj->message = "Name is not capitalized";
                return $returnObj;
            }
        }

        if(!in_array($validatedOrder['currency'], ["TWD", "USD"])){
            $returnObj->message = "Currency format is wrong";
            return $returnObj;
        }

        if($validatedOrder['currency'] == "USD"){
            $validatedOrder['currency'] = "TWD";
            $validatedOrder['price'] = $validatedOrder['price'] * 31;
        }

        if($validatedOrder['price'] > 2000){
            $returnObj->message = "Price is over 2000";
            return $returnObj;
        }

        $returnObj->error = false;
        $returnObj->arrayOrder = $validatedOrder;

        return $returnObj;
    }
}
