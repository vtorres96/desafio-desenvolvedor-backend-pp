<?php

namespace Tests\unit\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserService;
use Exception;
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
     * Test successful create user.
     *
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

        $this->userRepository->shouldReceive('beginTransaction')->once();
        $this->userModel->shouldReceive('toArray')->andReturn($data);
        $this->userRepository->shouldReceive('getByCpfCnpjOrEmail')->once()
            ->with($data['cpf_cnpj'], $data['email'])->andReturn([]);
        $this->userRepository->shouldReceive('create')->once()->andReturn($this->userModel);
        $this->userRepository->shouldReceive('commitTransaction')->once();
        $this->userRepository->shouldNotReceive('rollbackTransaction');
        $response = $this->getConcreteClass()->create($data);

        $this->assertIsArray($response);
        $this->assertEquals($data, $response);
    }

    /**
     * Test successful get user by id.
     *
     * @throws \Exception
     */
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

    /**
     * Test successful update user.
     *
     * @throws \Exception
     */
    public function testUpdateUserSuccessfully(): void
    {
        $userId = $this->faker->numberBetween(1, 10);
        $data = [
            'name' => $this->faker->name(),
            'cpf_cnpj' => $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ];

        $this->userRepository->shouldReceive('beginTransaction')->once();
        $this->userRepository->shouldReceive('getByCpfCnpjOrEmail')->once()
            ->with($data['cpf_cnpj'], $data['email'])->andReturn([]);
        $this->userRepository->shouldReceive('update')->once()->with($userId, Mockery::on(function ($arg) use ($data) {
            return $arg['password'] !== $data['password']; // Hash password changes
        }));
        $this->userRepository->shouldReceive('commitTransaction')->once();
        $this->userRepository->shouldNotReceive('rollbackTransaction');

        $this->getConcreteClass()->update($userId, $data);
    }

    /**
     * Test create user with duplicate CPF/CNPJ or email.
     *
     * @throws \Exception
     */
    public function testCreateUserWithDuplicateCpfCnpjOrEmail(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Já existe um usuário com o CPF/CNPJ ou e-mail fornecido.');

        $data = [
            'name' => $this->faker->name(),
            'cpf_cnpj' => $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ];

        $this->userRepository->shouldReceive('beginTransaction')->once();
        $this->userRepository->shouldReceive('getByCpfCnpjOrEmail')->once()
            ->with($data['cpf_cnpj'], $data['email'])->andReturn([$data]);
        $this->userRepository->shouldNotReceive('create');
        $this->userRepository->shouldReceive('rollbackTransaction')->once();
        $this->userRepository->shouldNotReceive('commitTransaction');

        $this->getConcreteClass()->create($data);
    }

    /**
     * Test update user with duplicate CPF/CNPJ or email.
     *
     * @throws \Exception
     */
    public function testUpdateUserWithDuplicateCpfCnpjOrEmail(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Já existe um usuário com o CPF/CNPJ ou e-mail fornecido.');

        $userId = $this->faker->numberBetween(1, 10);
        $data = [
            'name' => $this->faker->name(),
            'cpf_cnpj' => $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ];

        $this->userRepository->shouldReceive('getByCpfCnpjOrEmail')->once()
            ->with($data['cpf_cnpj'], $data['email'])->andReturn([$data]);
        $this->userRepository->shouldNotReceive('update');
        $this->userRepository->shouldReceive('rollbackTransaction')->once();
        $this->userRepository->shouldNotReceive('commitTransaction');

        $this->getConcreteClass()->update($userId, $data);
    }

    /**
     * Test get user by CPF/CNPJ or email successfully.
     *
     * @throws \Exception
     */
    public function testGetByCpfCnpjOrEmailSuccessfully(): void
    {
        $cpfCnpj = $this->faker->numerify('###########');
        $email = $this->faker->unique()->safeEmail();
        $data = [
            'id' => $this->faker->numberBetween(1, 10),
            'name' => $this->faker->name(),
            'cpf_cnpj' => $cpfCnpj,
            'email' => $email,
            'password' => $this->faker->password(),
        ];

        $this->userRepository->shouldReceive('getByCpfCnpjOrEmail')->once()
            ->with($cpfCnpj, $email)->andReturn([$data]);

        $response = $this->getConcreteClass()->getByCpfCnpjOrEmail($cpfCnpj, $email);

        $this->assertIsArray($response);
        $this->assertEquals([$data], $response);
    }
}
