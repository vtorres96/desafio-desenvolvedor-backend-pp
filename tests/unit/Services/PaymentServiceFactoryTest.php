<?php

namespace Tests\unit\Services;

use App\Services\PaymentService;
use App\Services\PaymentServiceFactory;
use Tests\TestCase;

/**
 * Class PaymentServiceFactoryTest
 * @package   Tests\unit\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentServiceFactoryTest extends TestCase
{
    public function testFabrica()
    {
        $factory = new PaymentServiceFactory();
        $this->assertInstanceOf(PaymentServiceFactory::class, $factory);
        $servico = $factory();
        $this->assertInstanceOf(PaymentService::class, $servico);
    }
}
