<?php

namespace Tests\unit\Repositories;

use App\Repositories\PaymentRepositoryFactory;
use App\Repositories\PaymentRepository;
use Tests\TestCase;

/**
 * Class PaymentRepositoryFactoryTest
 * @package   Tests\unit\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentRepositoryFactoryTest extends TestCase
{
    public function testFabrica()
    {
        $factory = new PaymentRepositoryFactory();

        $this->assertInstanceOf(PaymentRepositoryFactory::class, $factory);

        $servico = $factory();

        $this->assertInstanceOf(PaymentRepository::class, $servico);
    }
}
