<?php

namespace Tests\unit\Services;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Repositories\PaymentRepositoryInterface;
use App\Services\Notification\EmailService;
use App\Services\Notification\EmailServiceInterface;
use App\Services\Payment\AuthorizerService;
use App\Services\PaymentService;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use App\Services\Payment\AuthorizerServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    /** @var \Mockery\MockInterface|\App\Repositories\UserRepositoryInterface $paymentRepository */
    private MockInterface $paymentRepository;

    /** @var \Mockery\MockInterface|\App\Services\UserServiceInterface $userService */
    private MockInterface $userService;

    /** @var \Mockery\MockInterface|\App\Services\Payment\AuthorizerServiceInterface $authorizerService */
    private MockInterface $authorizerService;

    /** @var \Mockery\MockInterface|\App\Services\Notification\EmailServiceInterface $emailService */
    private MockInterface $emailService;

    /** @var \Mockery\MockInterface|\App\Models\Payment $paymentModel */
    private MockInterface $paymentModel;

    /** @var \Illuminate\Database\Eloquent\Builder|\Mockery\LegacyMockInterface|\Mockery\MockInterface $eloquentBuilder */
    private $eloquentBuilder;


    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentModel = Mockery::mock(Payment::class);
        $this->eloquentBuilder = Mockery::mock(Builder::class);
        $this->paymentRepository = Mockery::mock(
            PaymentRepository::class,
            PaymentRepositoryInterface::class
        );
        $this->userService = Mockery::mock(
            UserService::class,
            UserServiceInterface::class
        );
        $this->authorizerService = Mockery::mock(
            AuthorizerService::class,
            AuthorizerServiceInterface::class
        );
        $this->emailService = Mockery::mock(
            EmailService::class,
            EmailServiceInterface::class
        );

        $this->paymentModel->shouldReceive('newQuery')->andReturns($this->eloquentBuilder);
    }

    /**
     * @return \App\Services\PaymentService
     */
    public function getConcreteClass(): PaymentService
    {
        return new PaymentService(
            $this->paymentRepository,
            $this->userService,
            $this->authorizerService,
            $this->emailService
        );
    }

    /**
     * Test successful transfer.
     *
     * @throws Exception
     */
    public function testTransferSuccessfully(): void
    {
        $data = [
            'payer' => 1,
            'payee' => 2,
            'value' => 100.0,
        ];

        $payer = [
            'id' => 1,
            'type' => UserServiceInterface::COMMON,
            'balance' => 200.0,
            'email' => 'payer@example.com',
        ];

        $payee = [
            'id' => 2,
            'type' => UserServiceInterface::SHOPKEEPER,
            'balance' => 50.0,
            'email' => 'payee@example.com',
        ];

        $this->paymentRepository->shouldReceive('beginTransaction')->once();
        $this->paymentModel->shouldReceive('toArray')->andReturn($data);
        $this->paymentRepository->shouldReceive('create')->once()->andReturn($this->paymentModel);
        $this->paymentRepository->shouldReceive('commitTransaction')->once();
        $this->paymentRepository->shouldNotReceive('rollbackTransaction');

        $this->userService->shouldReceive('findById')->with($data['payer'])->andReturn($payer);
        $this->userService->shouldReceive('findById')->with($data['payee'])->andReturn($payee);
        $this->userService->shouldReceive('update')->twice();

        $this->authorizerService->shouldReceive('checkTransactionIsAuthorized')->once()->andReturn(true);

        $this->emailService->shouldReceive('sendEmail')->twice();

        $paymentService = $this->getConcreteClass();
        $response = $paymentService->transfer($data);

        $this->assertIsArray($response);
        $this->assertEquals($data, (array)$response);
    }

    /**
     * Test transfer with insufficient balance.
     *
     * @throws Exception
     */
    public function testTransferInsufficientBalance(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Saldo insuficiente para realizar o pagamento');

        $data = [
            'payer' => 1,
            'payee' => 2,
            'value' => 300.0,
        ];

        $payer = [
            'id' => 1,
            'type' => UserServiceInterface::COMMON,
            'balance' => 200.0,
            'email' => 'payer@example.com',
        ];

        $this->paymentRepository->shouldReceive('beginTransaction')->once();
        $this->paymentRepository->shouldReceive('rollbackTransaction')->once();

        $this->userService->shouldReceive('findById')->with($data['payer'])->andReturn($payer);

        $paymentService = $this->getConcreteClass();
        $paymentService->transfer($data);
    }

    /**
     * Test transfer when authorization fails.
     *
     * @throws Exception
     */
    public function testTransferAuthorizationFailed(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Transação negada');

        $data = [
            'payer' => 1,
            'payee' => 2,
            'value' => 100.0,
        ];

        $payer = [
            'id' => 1,
            'type' => UserServiceInterface::COMMON,
            'balance' => 200.0,
            'email' => 'payer@example.com',
        ];

        $payee = [
            'id' => 2,
            'type' => UserServiceInterface::SHOPKEEPER,
            'balance' => 50.0,
            'email' => 'payee@example.com',
        ];

        $this->paymentRepository->shouldReceive('beginTransaction')->once();
        $this->paymentRepository->shouldReceive('rollbackTransaction')->once();

        $this->userService->shouldReceive('findById')->with($data['payer'])->andReturn($payer);
        $this->userService->shouldReceive('findById')->with($data['payee'])->andReturn($payee);

        $this->authorizerService->shouldReceive('checkTransactionIsAuthorized')->once()->andReturn(false);

        $paymentService = $this->getConcreteClass();
        $paymentService->transfer($data);
    }
}
