<?php

namespace App\Interfaces;

interface OrderRepositoryInterface
{
    public function getAllOrders();
    public function getOrderById($orderId);
    public function createOrder(array $orderDetails);
    public function updateOrderById($orderId, array $orderDetails);
    public function deleteOrderById($orderId);
    public function getAmountTotal($price, $quantity);
    public function getAllOrdersWithPaginate($per_page);
    public function getAllOrdersAvailable();
    public function getAllOrdersPending();
}
