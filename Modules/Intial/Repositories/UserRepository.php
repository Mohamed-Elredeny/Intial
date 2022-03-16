<?php

namespace Modules\Intial\Repositories;
use Modules\Intial\Entities\User;
use Modules\Intial\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface {


    public function getAllOrders()
    {
       return  User::get();
    }

    public function getOrderById($orderId)
    {
        // TODO: Implement getOrderById() method.
    }

    public function deleteOrder($orderId)
    {
        // TODO: Implement deleteOrder() method.
    }

    public function createOrder(array $orderDetails)
    {
        // TODO: Implement createOrder() method.
    }

    public function updateOrder($orderId, array $newDetails)
    {
        // TODO: Implement updateOrder() method.
    }
}
