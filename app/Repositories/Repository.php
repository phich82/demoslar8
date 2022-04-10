<?php

namespace App\Repositories;

abstract class Repository
{
    public $model;

    /**
     * Configure Model
     *
     * @return string
     */
    abstract protected function model();

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = app()->make($this->model());
    }

    /**
     * __call
     *
     * @param  string $method
     * @param  array $arguments
     *
     * @return QueryBuilder
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            return $this->{$method}(...$arguments);
        }
        return $this->model->newQuery->{$method}(...$arguments);
    }

    /**
     * Insert many rows at once time
     *
     * @param  array $params
     * @return bool
     */
    public function insertMany($params)
    {
        if (empty($params)) {
            return false;
        }
        return $this->model->insert($params);
    }

    /**
     * Update by conditions
     *
     * @param  array $conditions
     * @param  array $params
     * @return bool
     */
    public function updateByConditions(array $conditions, array $params)
    {
        if (empty($conditions) || empty($params)) {
            return false;
        }
        $model = $this->model;
        foreach ($conditions as $field => $condition) {
            $where = is_array($condition) ? 'whereIn' : 'where';
            $model = $model->{$where}($field, $condition);
        }
        return $model->update($params);
    }

    /**
     * Delete records by conditions
     *
     * @param  array $conditions
     * @return bool
     */
    public function deleteByConditions($conditions = [])
    {
        if (empty($conditions)) {
            return false;
        }
        $model = $this->model;
        foreach ($conditions as $field => $condition) {
            $where = is_array($condition) ? 'whereIn' : 'where';
            $model = $model->{$where}($field, $condition);
        }
        return $model->delete();
    }
}
