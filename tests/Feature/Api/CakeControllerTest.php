<?php

namespace Tests\Feature\Api;

use App\Jobs\SendEmailCakeAvailableJob;
use Tests\TestCase;
use App\Models\Cake;
use App\Utilities\Result;
use Illuminate\Support\Arr;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

class CakeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $group = '/api/cakes';

    protected $response_indexes = [
        'success' => 'boolean',
        'data' => 'array|null',
        'message' => 'null|string',
        'errors' => 'null|string'
    ];

    protected $object_default_model = [
        'id' => 'string',
        'name' => 'string',
        'price' => 'double',
        'weight' => 'string',
        'quantity' => 'integer',
        'created_at' => 'string|null',
        'updated_at' => 'string|null',
        'deleted_at' => 'null|string',
        'available' => 'integer'
    ];

    /**
     * A test cake list.
     *
     * @return void
     */
    public function test_should_cake_list_all_returns_successful()
    {
        // total of list
        $total_matches_data = 3;
        Cake::factory($total_matches_data)->create();
        // object_default_model
        $object_default_model = $this->object_default_model;
        // removendo o indice e available, pois não existe na listagem
        $object_default_model = Arr::except($object_default_model, ['available']);
        $object_default_model = Arr::dot([
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
     * A test cake create.
     *
     * @return void
     */
    public function test_should_cake_register_returns_successful()
    {
        $params = [
            'name' => 'Strawberry',
            'price' => '13.0',
            'weight' => 500,
            'quantity' => 1,
        ];
        // object_default_model
        $object_default_model = $this->object_default_model;
        // na criação price vem como string
        $object_default_model['price'] = 'string';
        // removendo o indice deleted_at e available, pois não existe no cadastro
        $object_default_model = Arr::except($object_default_model, ['deleted_at','available']);
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
    }

    /**
     * A test cake create.
     *
     * @return void
     */
    public function test_should_cake_update_returns_successful()
    {

        // total of list
        $total_fakes_created = 3;
        // cria os fakes e retorna o primeiro cadastrado
        $row = Cake::factory($total_fakes_created)
            ->create()
            ->first()
            ->toArray();
        //
        $params = $row;
        // muda o nome do bolo
        $params['name'] = 'Chocolate test';
        $params['weight'] = 200;
        // object_default_model array list
        $object_default_model = $this->object_default_model;
        // adicion
        $object_default_model = Arr::dot(['data' => $object_default_model]);
        // efetua a requisição fake
        $response = $this->putJson(
            $this->group . '/' . $params['id'],
            $params
        );
        $response->assertStatus(Response::HTTP_OK);
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
            SendEmailCakeAvailableJob::class
        ]);
        Queue::assertNothingPushed();
    }

    /**
     * A test cake create.
     *
     * @return void
     */
    public function test_should_cake_delete_returns_successful()
    {
        // total of list
        $total_fakes_created = 3;
        // cria os fakes e retorna o primeiro cadastrado
        $row = Cake::factory($total_fakes_created)
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
    }
}
