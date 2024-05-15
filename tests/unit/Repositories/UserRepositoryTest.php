<?php

namespace Tests\unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Class UserRepositoryTest
 * @package Tests\unit\Repositories
 */
class UserRepositoryTest extends TestCase
{
    use WithFaker;

    /** @var MockInterface|User */
    private MockInterface $userModel;

    /** @var UserRepository */
    private UserRepository $userRepository;

    /** @var \Illuminate\Database\Eloquent\Builder|\Mockery\LegacyMockInterface|\Mockery\MockInterface $eloquentBuilder */
    private $eloquentBuilder;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userModel = Mockery::mock(User::class);
        $this->eloquentBuilder = Mockery::mock(Builder::class);

        $this->userModel->shouldReceive('newQuery')->andReturn($this->eloquentBuilder);
    }

    /**
     * @return UserRepository
     */
    public function getConcreteClass(): UserRepository
    {
        return new UserRepository(
            $this->userModel
        );
    }

    public function testInstanceOfPaymentRepository(): void
    {
        $repository = $this->getConcreteClass();

        $this->assertInstanceOf(UserRepository::class, $repository);
    }

    /**
     * Test get user by cpf cnp or email.
     *
     * @return void
     * @throws \Exception
     */
    public function testGetByCpfCnpjOrEmail(): void
    {
        $cpfCnpj = $this->faker->numerify('###########');
        $email = $this->faker->unique()->safeEmail();
        $userData = [
            ['id' => 1, 'name' => 'User 1', 'cpf_cnpj' => $cpfCnpj, 'email' => $email],
            ['id' => 2, 'name' => 'User 2', 'cpf_cnpj' => $cpfCnpj, 'email' => $email],
        ];

        $this->eloquentBuilder->shouldReceive('where')->with('cpf_cnpj', $cpfCnpj)->andReturnSelf();
        $this->eloquentBuilder->shouldReceive('orWhere')->with('email', $email)->andReturnSelf();
        $this->eloquentBuilder->shouldReceive('get')->andReturn(collect($userData));

        $result = $this->getConcreteClass()->getByCpfCnpjOrEmail($cpfCnpj, $email);

        $this->assertEquals($userData, $result);
    }
}
