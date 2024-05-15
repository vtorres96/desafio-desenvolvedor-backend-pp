<?php

namespace Tests\unit\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Class UserServiceTest
 * @package   Tests\unit\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserServiceTest extends TestCase
{
    use WithFaker;

    /** @var \Mockery\MockInterface|\App\Models\User $userModel */
    private MockInterface $userModel;

    /** @var \Mockery\MockInterface|\App\Repositories\UserRepositoryInterface $userRepository */
    private MockInterface $userRepository;

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
        $this->userRepository = Mockery::mock(
            UserRepository::class,
            UserRepositoryInterface::class
        );

        $this->userModel->shouldReceive('newQuery')->andReturns($this->eloquentBuilder);
    }

    /**
     * @return \App\Services\UserService
     */
    public function getConcreteClass(): UserService
    {
        return new UserService(
            $this->userRepository
        );
    }

    /**
     * @throws \Exception
     */
    public function testCreateUserSuccessfully(): void
    {
        $data = [
            'name' => $this->faker->name(),
            'cpf_cnpj' => $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ];

        $this->userModel->shouldReceive('toArray')->andReturn($data);
        $this->userRepository->shouldReceive('getByCpfCnpjOrEmail')->once()
            ->with($data['cpf_cnpj'], $data['email'])->andReturn([]);
        $this->userRepository->shouldReceive('beginTransaction')->once();
        $this->eloquentBuilder->shouldReceive('create')->andReturn($this->eloquentBuilder);
        $this->eloquentBuilder->shouldReceive('toArray')->andReturn($this->eloquentBuilder);
        $this->userRepository->shouldReceive('create')->once()->andReturn($this->userModel);
        $this->userRepository->shouldReceive('commitTransaction')->once();
        $this->userRepository->shouldNotReceive('rollbackTransaction');
        $response = $this->getConcreteClass()->create($data);

        $this->assertIsArray($response);
        $this->assertEquals($data, $response);
    }

    public function testFindUserByIdSuccessfully(): void
    {
        $userId = $this->faker->numberBetween(1, 10);
        $data = [
            'id' => $userId,
            'name' => $this->faker->name(),
            'cpf_cnpj' => $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ];

        $this->userRepository->shouldReceive('findById')->once()->with($userId)->andReturn($data);

        $response = $this->getConcreteClass()->findById($userId);

        $this->assertIsArray($response);
        $this->assertEquals($data, $response);
    }
}
