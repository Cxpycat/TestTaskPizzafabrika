<?php

use App\Controllers\Api\V1\OrderController;
use App\Controllers\Documentation;
use App\Kernel\Router\Route;
use App\Middleware\AuthMiddleware;

return [
    Route::get('/api/v1/orders', [OrderController::class, 'listOrders'], [AuthMiddleware::class]),
    Route::post('/api/v1/orders', [OrderController::class, 'createOrder']),

    Route::post('/api/v1/orders/{order_id}/items', [OrderController::class, 'addItemToOrder']),

    Route::get('/api/v1/orders/{order_id}', [OrderController::class, 'getOrderDetails']),

    Route::post('/api/v1/orders/{order_id}/done', [OrderController::class, 'markOrderAsDone'], [AuthMiddleware::class]),
    Route::get('/swagger', [Documentation::class, 'index']),
];
