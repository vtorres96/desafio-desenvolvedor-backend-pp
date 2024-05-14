<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 * @package   App\Repositories
 * @author    Victor Tores <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
abstract class BaseRepository
{
    /** @var Model $model */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $queryBuilder = $this->model->newQuery()->findOrFail($id);
        return $queryBuilder->toArray();
    }

    /**
     * @param array $data
     * @return integer
     */
    public function create(array $data): int
    {
        return $this->model->newQuery()->insertGetId(
            $this->model->fill($data)->getAttributes()
        );
    }

    /**
     * @param int $id
     * @return bool|mixed|null
     */
    public function delete(int $id): bool
    {
        $queryBuilder = $this->model->newQuery()->findOrFail($id);
        return $queryBuilder->delete();
    }

    /**
     * @param $id
     * @param array $data
     * @return bool|int
     */
    public function update($id, array $data): bool
    {
        $queryBuilder = $this->model->newQuery()->findOrFail($id);
        return $queryBuilder->update($data);
    }

    /**
     * @throws \Throwable
     */
    public function beginTransaction(): void
    {
        $this->model->getConnection()->beginTransaction();
    }

    /**
     * @throws \Throwable
     */
    public function commitTransaction(): void
    {
        $this->model->getConnection()->commit();
    }

    /**
     * @param array $options
     * @throws \Exception
     */
    public function rollbackTransaction(array $options = []): void
    {
        $level = $options['level'] ?? null;
        $this->model->getConnection()->rollBack($level);
    }
}
