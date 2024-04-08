<?php

namespace App\Controllers\Api\V1;

use App\Kernel\Controller\Controller;
use App\Models\Order;

class OrderController extends Controller
{

    public function listOrders()
    {
        $filters = [];

        if (!is_null($this->request->input('done'))) {
            $filters['status'] = (int)$this->request->input('done');
        }

        $this->response->ajaxSuccess([Order::get($filters, ['id', 'items', 'status'])]);
    }

    public function getOrderDetails($order_id)
    {
        if (!$order = Order::find($order_id)) {
            $this->response->ajaxError('Order not found', 404);
        }

        $this->response->ajaxSuccess([
            'order_id' => $order->id,
            'items' => $order->items,
            'done' => $order->status,
        ]);
    }

    public function createOrder()
    {
        $validation = $this->request->validate([
            'items' => ['required', 'array'],
        ]);

        if (!$validation) {
            $this->response->ajaxError($this->request->errors());
        }

        $order = Order::create([
            'id' => Order::createId(),
            'items' => $this->request->input('items'),
            'status' => 0,
        ]);

        $this->response->ajaxSuccess([
            'order_id' => $order->id,
            'items' => $order->items,
            'done' => $order->status,
        ]);
    }

    public function addItemToOrder($order_id)
    {
        $validation = $this->request->validate([
            'items' => ['required', 'array'],
        ]);

        if (!$validation) {
            $this->response->ajaxError($this->request->errors());
        }

        if (!$order = Order::find($order_id)) {
            $this->response->ajaxError('Order not found', 404);
        }

        if ((bool)$order->status === true) {
            $this->response->ajaxError('Order already done');
        }


        $order->update([
            'items' => array_merge($order->items, $this->request->input('items'))
        ]);

        $this->response->ajaxSuccess();
    }

    public function markOrderAsDone($order_id)
    {
        if (!$order = Order::find($order_id)) {
            $this->response->ajaxError('Order not found', 404);
        }

        if ((bool)$order->status === true) {
            $this->response->ajaxError('Order already done');
        }

        $order->update([
            'status' => true
        ]);

        $this->response->ajaxSuccess();
    }

}