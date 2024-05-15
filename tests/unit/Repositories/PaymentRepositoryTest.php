<?php

namespace Tests\Unit\Repositories;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class PaymentRepositoryTest extends TestCase
{
    /** @var MockInterface|Payment */
    private MockInterface $model;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = Mockery::mock(Payment::class);
    }

    public function testInstanceOfPaymentRepository(): void
    {
        $repository = $this->getConcreteClass();

        $this->assertInstanceOf(PaymentRepository::class, $repository);
    }

    /**
     * @return PaymentRepository
     */
    public function getConcreteClass(): PaymentRepository
    {
        return new PaymentRepository(
            $this->model
        );
    }
}
