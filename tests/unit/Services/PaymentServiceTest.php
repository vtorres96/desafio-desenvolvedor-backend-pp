<?php

namespace Tests\unit\Services;

use App\Repositories\PaymentRepository;
use App\Repositories\PaymentRepositoryInterface;
use App\Services\Notification\EmailService;
use App\Services\Notification\EmailServiceInterface;
use App\Services\Payment\AuthorizerService;
use App\Services\PaymentService;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use App\Services\Payment\AuthorizerServiceInterface;
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

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
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

//    public function testCreateAuthorizedPayment()
//    {
//        $this->authorizerService->shouldReceive('checkTransactionIsAuthorized')->andReturn(true);
//    }
}
