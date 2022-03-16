<?php

namespace Modules\Intial\Interfaces;

interface UserRepositoryInterface
{
    public function getAllOrders();
    public function getOrderById($orderId);
    public function deleteOrder($orderId);
    public function createOrder(array $orderDetails);
    public function updateOrder($orderId, array $newDetails);
}
