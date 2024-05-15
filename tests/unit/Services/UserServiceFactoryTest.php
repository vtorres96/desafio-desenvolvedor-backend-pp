<?php

namespace Tests\unit\Services;

use App\Services\UserService;
use App\Services\UserServiceFactory;
use Tests\TestCase;

/**
 * Class UserServiceFactoryTest
 * @package   Tests\unit\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserServiceFactoryTest extends TestCase
{
    public function testFabrica()
    {
        $factory = new UserServiceFactory();
        $this->assertInstanceOf(UserServiceFactory::class, $factory);
        $servico = $factory();
        $this->assertInstanceOf(UserService::class, $servico);
    }
}
