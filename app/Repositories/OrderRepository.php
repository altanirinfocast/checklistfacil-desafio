<?php

namespace App\Repositories;

use App\Models\Order;
use App\Interfaces\OrderRepositoryInterface;

class OrderRepository extends Order implements OrderRepositoryInterface
{

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Order
     */
    public function getAllOrdersWithPaginate($per_page = 10)
    {
        return $this->paginate($per_page);
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Order
     */
    public function getAllOrders()
    {
        return $this->all();
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Order
     */
    public function getOrderById($orderId)
    {
        $order = $this->with('customer', 'cake')->findOrFail($orderId);
        $order = collect($order)->except('cake_id', 'customer_id');
        return $order;
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Order
     */
    public function createOrder(array $orderDetails)
    {
        return $this->create($orderDetails);
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Order
     */
    public function updateOrderById($orderId, array $orderDetails)
    {
        return $this->where($this->primaryKey, $orderId)->update($orderDetails) > 0;
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Order
     */
    public function deleteOrderById($orderId)
    {
        return $this->destroy($orderId);
    }

    /**
     * Get amount total
     *
     * @return float
     */
    public function getAmountTotal($price, $quantity)
    {
        return $price * $quantity;
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Order
     */
    public function getAllOrdersAvailable()
    {
        return $this->where('status', self::STATUS_AVAILABLE)->get();
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Order
     */
    public function getAllOrdersPending()
    {
        return $this->where('status', self::STATUS_PENDING)->get();
    }
}
