<?php

namespace Tests\unit\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Services\UserServiceInterface;
use Faker\Factory;
use App\Http\Requests\UserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class UserControllerTest
 * @package   Tests\unit\Http\Controllers
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserControllerTest extends TestCase
{
    /** @var \App\Services\UserServiceInterface|Mockery\MockInterface $userService */
    private $userService;

    /** @var \Faker\Generator */
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = Mockery::mock(UserServiceInterface::class);
        $this->faker = Factory::create('pt_BR');
    }

    private function getConcreteClass(): UserController
    {
        return new UserController($this->userService);
    }

    /**
     * Test successful create user.
     *
     */
    public function testCreateSuccessfully(): void
    {
        $data = [
            'name' => $this->faker->name(),
            'cpf_cnpj' => $this->faker->numberBetween(1, 11),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'type' => $this->faker->randomElement(['common', 'shopkeeper']),
            'balance' => $this->faker->randomFloat(2, 0, 1000),
        ];
        $response = [
            'data' => $data
        ];

        $request = Mockery::mock(UserRequest::class);
        $request->shouldReceive('validated')->andReturn($data);
        $this->userService->shouldReceive('create')->with($data)->andReturn($response);

        $response = $this->getConcreteClass()->create($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * Test successful get user by id.
     *
     */
    public function testFindById(): void
    {
        $id = $this->faker->numberBetween(1, 10);
        $data = [
            'name' => $this->faker->name(),
            'cpf_cnpj' => $this->faker->numberBetween(1, 11),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'type' => $this->faker->randomElement(['common', 'shopkeeper']),
            'balance' => $this->faker->randomFloat(2, 0, 1000),
        ];
        $response = [
            'data' => $data
        ];

        $this->userService->shouldReceive('findById')->with($id)->andReturn($response);

        $response = $this->getConcreteClass()->findById($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
