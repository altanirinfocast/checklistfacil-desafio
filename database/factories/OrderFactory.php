<?php

namespace Database\Factories;

use App\Models\Cake;
use App\Interfaces\OrderRepositoryInterface;
use App\Models\Customer;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fake = fake('pt_BR');
        $quantity = rand(1, 10);
        $orderRepository = new OrderRepository();
        $cakes = Cake::all();
        $cakes = $cakes[rand(0, 3)];
        $customer = Customer::create([
            'name' => $fake->name(),
            'email' => $fake->unique()->safeEmail(),
        ]);
        # $cakes->available ? 'pending' : 'unavailable';// verificar esta validação
        $status = Order::STATUS_PENDING;
        return [
            'customer_id' => $customer->id,
            'cake_id' => $cakes->id,
            'quantity' => $quantity,
            'amount' => $orderRepository->getAmountTotal($cakes->price, $quantity),
            'status' => $status
        ];
    }
}
