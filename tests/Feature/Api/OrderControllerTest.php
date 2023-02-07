<?php

namespace Tests\Feature\Api;

use App\Jobs\SendEmailNotificationJob;
use Tests\TestCase;
use App\Models\Cake;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $group = '/api/orders';

    protected $response_indexes = [
        'success' => 'boolean',
        'data' => 'array|null',
        'message' => 'null|string',
        'errors' => 'null|string'
    ];

    protected $object_default_model = [
        'id' => 'string',
        'cake_id' => 'string',
        'quantity' => 'integer',
        'customer_id' => 'string',
        'status' => 'string|null',
        'amount' => 'double',
        'created_at' => 'string|null',
        'updated_at' => 'string|null',
        'deleted_at' => 'null|string',
    ];

    /**
     * A test order list.
     *
     * @return void
     */
    public function test_should_order_list_all_returns_successful()
    {
        // total of list
        $total_matches_data = 4;
        // create cakes
        Cake::factory($total_matches_data)->create();
        // create orders
        Order::factory($total_matches_data)->create();
        // object_default_model
        $object_default_model = $this->object_default_model;
        // removendo o indice e available, pois não existe na listagem
        $object_default_model = Arr::except($object_default_model, ['available']);
        $object_default_model = Arr::dot(
            [
                'data' => [
                    $object_default_model
                ]
            ]
        );
        // efetua a requisição fake
        $response = $this->getJson(
            $this->group
        );
        $response->assertStatus(Response::HTTP_OK);

        // verificando total de registros no indice data
        $response->assertJsonCount(count($this->response_indexes)); // success, data, message, errors

        // verificando total de registros no indice data
        $response->assertJson(function (AssertableJson $json) use ($total_matches_data, $object_default_model) {
            // se tem todos os indices de resposta padrão
            $json->hasAll(array_keys($this->response_indexes));
            // se tem os mesmos tipos de cada indice
            $json->whereAllType($this->response_indexes);
            $json->whereAllType($object_default_model);
            // se tem a mesma quantidade registros
            $json->count('data', $total_matches_data)->etc(); // se tem total de indices de data
        });
    }

    /**
     * A test order create.
     *
     * @return void
     */
    public function test_should_order_register_returns_successful()
    {
        // total of list
        $total_matches_data = 4;
        // create cakes
        $cake = Cake::factory($total_matches_data)
            ->create()
            ->first()
            ->toArray();
        //
        $fake = fake('pt_BR');
        $params = [
            'name' => $fake->name(),
            'email' => $fake->safeEmail(),
            'cake_id' => $cake['id'],
            'quantity' => 1,
        ];
        // object_default_model
        $object_default_model = $this->object_default_model;
        // removendo o indice deleted_at e available, pois não existe no cadastro
        $object_default_model = Arr::except($object_default_model, ['deleted_at', 'available']);
        $object_default_model = Arr::dot(['data' => $object_default_model]);

        // efetua a requisição fake
        $response = $this->postJson(
            $this->group,
            $params
        );
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonCount(4); // success, data, message, errors
        // verificando total de registros no indice data
        $response->assertJson(function (AssertableJson $json) use ($object_default_model) {
            // se tem todos os indices de resposta padrão
            $json->hasAll(array_keys($this->response_indexes));
            // se tem os mesmos tipos de cada indice
            $json->whereAllType($this->response_indexes);
            $json->whereAllType($object_default_model);
            // se tem a mesma quantidade registros
            $json->count('data', count($object_default_model))->etc(); // se tem total de indices de data
        });

        // chamando um job
        Queue::fake([
            SendEmailNotificationJob::class
        ]);
        Queue::assertNothingPushed();
    }

    /**
     * A test order create.
     *
     * @return void
     */
    public function test_should_order_delete_returns_successful()
    {
        // total of list
        $total_fakes_created = 4;
        // create cakes
        Cake::factory($total_fakes_created)
            ->create()
            ->first()
            ->toArray();
        // cria os fakes e retorna o primeiro cadastrado
        $row = Order::factory($total_fakes_created)
            ->create()
            ->last()
            ->toArray();
        //
        $params = $row;
        // object_default_model array list
        $object_default_model = $this->object_default_model;
        // adicion
        $object_default_model = Arr::dot(['data' => $object_default_model]);
        // efetua a requisição fake
        $response = $this->deleteJson(
            $this->group . '/' . $params['id']
        );
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        // verificando total de registros no indice data
        $response->assertNoContent(Response::HTTP_NO_CONTENT);

        // chamando um job
        Queue::fake([
            SendEmailNotificationJob::class
        ]);
        Queue::assertNothingPushed();
    }
}
