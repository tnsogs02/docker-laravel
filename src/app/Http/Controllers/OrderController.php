<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderController extends Controller
{
    private $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function convertOrder(OrderRequest $orderRequest)
    {
        $sanitizedOrderRequest = $orderRequest;
        $convertedOrder = $this->service->convert($sanitizedOrderRequest->validated());
        if($convertedOrder['error']){
            throw new HttpResponseException(response()->json([
                'status' => $convertedOrder['message'],
            ], 400));
        }
        return response()->json($convertedOrder['arrayOrder']);
    }
}
