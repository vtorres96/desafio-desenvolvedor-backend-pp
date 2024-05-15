<?php

namespace Tests\unit\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Connection;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Class BaseRepositoryTest
 * @package   Tests\unit\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class BaseRepositoryTest extends TestCase
{
    /** @var MockInterface|Model */
    private MockInterface $model;

    /** @var BaseRepository */
    private BaseRepository $repository;

    /** @var \Illuminate\Database\Eloquent\Builder|\Mockery\LegacyMockInterface|\Mockery\MockInterface $eloquentBuilder */
    private $eloquentBuilder;

    /** @var \Illuminate\Database\Connection|\Mockery\LegacyMockInterface|\Mockery\MockInterface $connection */
    private $connection;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = Mockery::mock(Model::class);
        $this->eloquentBuilder = Mockery::mock(Builder::class);
        $this->connection = Mockery::mock(Connection::class);
        $this->repository = new class ($this->model) extends BaseRepository {
            public function __construct($model)
            {
                parent::__construct($model);
            }
        };

        $this->model->shouldReceive('newQuery')->andReturns($this->eloquentBuilder);
    }

    /**
     * Test get user by id.
     */
    public function testFindById(): void
    {
        $id = 1;
        $data = ['id' => $id, 'name' => 'Test User'];

        $this->eloquentBuilder->shouldReceive('findOrFail')->with($id)->andReturn($this->model);
        $this->model->shouldReceive('toArray')->andReturn($data);

        $result = $this->repository->findById($id);

        $this->assertEquals($data, $result);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test User'];
        $attributes = ['name' => 'Test User'];

        $this->model->shouldReceive('fill')->with($data)->andReturnSelf();
        $this->model->shouldReceive('getAttributes')->andReturn($attributes);

        $this->model->shouldReceive('newQuery')->andReturn($this->eloquentBuilder);
        $this->eloquentBuilder->shouldReceive('create')->with($attributes)->andReturn($this->model);

        $result = $this->repository->create($data);

        $this->assertEquals($this->model, $result);
    }

    /**
     * Test delete user.
     */
    public function testDelete(): void
    {
        $id = 1;

        $this->eloquentBuilder->shouldReceive('findOrFail')->with($id)->andReturn($this->model);
        $this->model->shouldReceive('delete')->andReturn(true);

        $result = $this->repository->delete($id);

        $this->assertTrue($result);
    }

    /**
     * Test update user.
     *
     * @throws \Throwable
     */
    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'Updated User'];

        $this->model->shouldReceive('newQuery')->andReturn($this->eloquentBuilder);
        $this->eloquentBuilder->shouldReceive('findOrFail')->with($id)->andReturn($this->model);
        $this->model->shouldReceive('update')->with($data)->andReturn(true);

        $result = $this->repository->update($id, $data);

        $this->assertTrue($result);
    }

    /**
     * Test begin transaction.
     *
     * @throws \Throwable
     */
    public function testBeginTransaction(): void
    {
        $this->model->shouldReceive('getConnection')->andReturn($this->connection);
        $this->connection->shouldReceive('beginTransaction')->once();

        $this->repository->beginTransaction();
    }

    /**
     * Test commit transaction.
     *
     * @throws \Throwable
     */
    public function testCommitTransaction(): void
    {
        $this->model->shouldReceive('getConnection')->andReturn($this->connection);
        $this->connection->shouldReceive('commit')->once();

        $this->repository->commitTransaction();
    }

    /**
     * Test rollback transaction.
     *
     * @throws \Exception
     */
    public function testRollbackTransaction(): void
    {
        $this->model->shouldReceive('getConnection')->andReturn($this->connection);
        $this->connection->shouldReceive('rollBack')->with(null)->once();

        $this->repository->rollbackTransaction();
    }
}
