<?php

namespace Tests\unit\Repositories;

use App\Repositories\UserRepositoryFactory;
use App\Repositories\UserRepository;
use Tests\TestCase;

/**
 * Class UserRepositoryFactoryTest
 * @package   Tests\unit\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserRepositoryFactoryTest extends TestCase
{
    public function testFabrica()
    {
        $factory = new UserRepositoryFactory();

        $this->assertInstanceOf(UserRepositoryFactory::class, $factory);

        $servico = $factory();

        $this->assertInstanceOf(UserRepository::class, $servico);
    }
}
