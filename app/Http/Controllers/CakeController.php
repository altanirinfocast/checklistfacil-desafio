<?php

namespace App\Http\Controllers;

use Exception;
use App\Utilities\Result;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\CakeRepository;
use App\Http\Requests\StoreCakeRequest;
use App\Jobs\SendEmailCakeAvailableJob;
use App\Http\Requests\UpdateCakeRequest;
use App\Interfaces\CakeRepositoryInterface;

class CakeController extends Controller
{

    private CakeRepositoryInterface $cakeRepository;

    public function __construct(CakeRepositoryInterface $cakeRepository)
    {
        $this->cakeRepository = $cakeRepository;
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
            $result->setData($this->cakeRepository->getAllCakes());
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
     * @param  \App\Http\Requests\StoreCakeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCakeRequest $request)
    {
        try {
            $result = new Result();
            $created = $this->cakeRepository->createCake($request->all());
            $result->setData($created);
            $result->setSuccess(!empty($created->toArray()));
            $result->setMessage(trans('cake.messages.created')); // mensagem de sucesso na resposta padrão
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
     * @param  \App\Repositories\CakeRepository  $cake
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CakeRepository $cake)
    {
        try {
            $result = new Result();
            $cake->available;
            //caso true, adiciona os pedidos de emails listados
            if($request->with_orders)
                $cake->orders;

            $result->setData($cake);
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
     * @param  \App\Repositories\CakeRepository  $cake
     * @return \Illuminate\Http\Response
     */
    public function edit(CakeRepository $cake)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCakeRequest  $request
     * @param  \App\Repositories\CakeRepository  $cake
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCakeRequest $request, CakeRepository $cake)
    {
        try {
            $result = new Result();
            $updated = $this->cakeRepository->updateCakeById($request->cake->id, $request->all());
            if (!$updated) {
                throw new Exception(trans('system.messages.error'));
            }
            $cake = $this->cakeRepository->getCakeById($request->cake->id);
            $result->setData($cake);
            $result->setSuccess($updated);
            $result->setMessage(trans('system.messages.updated')); // mensagem de sucesso na resposta padrão
            // caso tenha disponível, mude a mensagem e dispara o job
            if ($cake->available) {
                //
                SendEmailCakeAvailableJob::dispatch();
            }
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
     * @param  \App\Repositories\CakeRepository  $cake
     * @return \Illuminate\Http\Response
     */
    public function destroy(CakeRepository $cake)
    {
        try {
            $result = new Result();
            $deleted = $this->cakeRepository->deleteCakeById($cake->id);
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
