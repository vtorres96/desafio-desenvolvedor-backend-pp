<?php

namespace Tests\unit\Http\Controllers;

use App\Http\Controllers\PaymentController;
use App\Http\Requests\PaymentRequest;
use App\Services\PaymentServiceInterface;
use Faker\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

/**
 * Class PaymentControllerTest
 * @package   Tests\unit\Http\Controllers
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentControllerTest extends TestCase
{
    /** @var \App\Services\PaymentServiceInterface|Mockery\MockInterface $paymentService */
    private $paymentService;

    /** @var \Faker\Generator */
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paymentService = Mockery::mock(PaymentServiceInterface::class);
        $this->faker = Factory::create('pt_BR');
    }

    private function obterClasseConcreta(): PaymentController
    {
        return new PaymentController($this->paymentService);
    }

    public function testTransfer(): void
    {
        $data = [
            'payer' => $this->faker->numberBetween(1, 10),
            'payee' => $this->faker->numberBetween(1, 10),
            'value' => $this->faker->randomFloat(2, 0, 1000),
        ];
        $response = [
            'data' => $data
        ];

        $request = Mockery::mock(PaymentRequest::class);
        $request->shouldReceive('validated')->andReturn($data);
        $this->paymentService->shouldReceive('transfer')->with($data)->andReturn($response);

        $retorno = $this->obterClasseConcreta()->transfer($request);

        $this->assertInstanceOf(JsonResponse::class, $retorno);
        $this->assertEquals(Response::HTTP_CREATED, $retorno->getStatusCode());
    }
}
