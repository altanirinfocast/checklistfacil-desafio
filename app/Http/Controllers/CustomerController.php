<?php

namespace App\Http\Controllers;

use Exception;
use App\Utilities\Result;
use Illuminate\Http\Response;
use App\Repositories\CustomerRepository;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Interfaces\CustomerRepositoryInterface;

class CustomerController extends Controller
{

    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $result = new Result();
            $result->setData($this->customerRepository->getAllCustomers());
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
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        try {
            $result = new Result();
            $created = $this->customerRepository->createCustomer($request->all());
            $result->setData($created);
            $result->setSuccess(!empty($created->toArray()));
            $result->setMessage(trans('customer.messages.created')); // mensagem de sucesso na resposta padrão
            if (!$result->response()['success']) {
                throw new Exception(trans('system.messages.error'));
            }
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
     * @param  \App\Repositories\CustomerRepository  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerRepository $customer)
    {
        try {
            $result = new Result();
            $result->setData($customer);
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
     * @param  \App\Repositories\CustomerRepository  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerRepository $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Repositories\CustomerRepository  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, CustomerRepository $customer)
    {
        try {
            $result = new Result();
            $updated = $this->customerRepository->updateCustomerById($request->customer->id, $request->all());
            if (!$updated) {
                throw new Exception(trans('system.messages.error'));
            }
            $result->setData($this->customerRepository->getCustomerById($request->customer->id));
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
     * @param  \App\Repositories\CustomerRepository  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerRepository $customer)
    {
        try {
            $result = new Result();
            $deleted = $this->customerRepository->deleteCustomerById($customer->id);
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
