<?php

namespace App\Jobs;

use App\Interfaces\CakeRepositoryInterface;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Notifications\CakeAvailableNotification;

class SendEmailCakeAvailableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        OrderRepositoryInterface $orderRepository,
        CakeRepositoryInterface $cakeRepository,
    ) {
        // envia apenas o que não estavam disponíveis
        $orders = $orderRepository->getAllOrdersPending();
        $orders->each(function (Order $order) use ($orderRepository, $cakeRepository) {
            $cake = $cakeRepository->getCakeById($order->cake_id);
            if ($cake->available) {
                // marco como enviado
                $orderRepository
                    ->updateOrderById($order->id, [
                        'status' => Order::STATUS_SENDED
                    ]);
                $order->customer->notify(new CakeAvailableNotification);
            }
        });
    }
}
