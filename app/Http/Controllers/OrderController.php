<?php

namespace App\Http\Controllers;

use Exception;
use App\Utilities\Result;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\OrderRepository;
use App\Jobs\SendEmailNotificationJob;
use App\Jobs\SendEmailCakeAvailableJob;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Interfaces\CakeRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\CustomerRepositoryInterface;

class OrderController extends Controller
{

    private OrderRepositoryInterface $orderRepository;
    private CustomerRepositoryInterface $customerRepository;
    private CakeRepositoryInterface $cakeRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CustomerRepositoryInterface $customerRepository,
        CakeRepositoryInterface $cakeRepository,
    )
    {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->cakeRepository = $cakeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $result = new Result();
            $rows = empty($request->paginate) ? $this->orderRepository->getAllOrders() : $this->orderRepository->getAllOrdersWithPaginate($request->per_page);
            $result->setData($rows);
            $total =empty($request->paginate) ?  $rows->count() : $rows->total();
            $result->setTotal($total);
            $result->setSuccess(true);
            return $result->response();
        } catch (Exception $e) {
            $result->setErrors($e->getMessage());
            $result->setSuccess(false);
            return $result->response();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $result = new Result();
            // mensagem final. Padrão é de sucesso
            $final_message = trans('cake.messages.not_available');
            // pegando o bolo pelo id
            $cake = $this->cakeRepository->getCakeById($request->cake_id);
            // cadastrando interessado
            $params_customer = $request->only($this->customerRepository->getFillableCustomers());
            $customer_created = $this->customerRepository->createCustomer($params_customer);
            // caso tenha falha, abre uma exceção
            if(empty($customer_created->toArray())) {
                throw new Exception(trans('system.messages.error'));
            }
            //
            $params_order = $request->all();
            $params_order['customer_id'] = $customer_created->id;
            $params_order['status'] = $cake->available ? 'available' : 'pending';
            // calculando o valor do pedido
            $params_order['amount'] = $this->orderRepository->getAmountTotal($cake->price, $request->quantity);
            $order_created = $this->orderRepository->createOrder($params_order);
            $result->setData($order_created);
            $result->setSuccess(!empty($order_created->toArray()));
            // caso tenha falha, abre uma exceção
            if(empty($order_created->toArray())) {
                throw new Exception(trans('system.messages.error'));
            }
            if (!$result->response()['success']) {
                throw new Exception(trans('system.messages.error'));
            }
            // caso tenha disponível, mude a mensagem e dispara o job
            if($cake->available) {
                $final_message = trans('order.messages.created');
                //
                SendEmailNotificationJob::dispatch();
            }
            $result->setMessage($final_message); // mensagem de sucesso final
            return response()->json($result->response(), Response::HTTP_CREATED);
        } catch (Exception $e) {
            $result->setErrors($e->getMessage());
            $result->setSuccess(false);
            return response()->json($result->response(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Repositories\OrderRepository  $order
     * @return \Illuminate\Http\Response
     */
    public function show(OrderRepository $order)
    {
        try {
            $result = new Result();
            $order = $this->orderRepository->getOrderById($order->id);
            $result->setData($order);
            $result->setSuccess(true);
            return $result->response();
        } catch (Exception $e) {
            $result->setErrors($e->getMessage());
            $result->setSuccess(false);
            return $result->response();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Repositories\OrderRepository  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderRepository $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Repositories\OrderRepository  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, OrderRepository $order)
    {
        try {
            $result = new Result();
            $updated = $this->orderRepository->updateOrderById($request->order->id, $request->all());
            if (!$updated) {
                throw new Exception(trans('system.messages.error'));
            }
            $result->setData($this->orderRepository->getOrderById($request->order->id));
            $result->setSuccess($updated);
            $result->setMessage(trans('system.messages.updated')); // mensagem de sucesso na resposta padrão
            return response()->json($result->response(), Response::HTTP_OK);
        } catch (Exception $e) {
            $result->setErrors($e->getMessage());
            $result->setSuccess(false);
            return response()->json($result->response(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Repositories\OrderRepository  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderRepository $order)
    {
        try {
            $result = new Result();
            $deleted = $this->orderRepository->deleteOrderById($order->id);
            if (!$deleted) {
                throw new Exception(trans('system.messages.error'));
            }
            $result->setData(null);
            $result->setSuccess($deleted);
            $result->setMessage(trans('system.messages.deleted')); // mensagem de sucesso na resposta padrão
            return response()->json($result->response(), Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            $result->setErrors($e->getMessage());
            $result->setSuccess(false);
            return response()->json($result->response(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
