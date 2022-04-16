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
     * Get table name of model directly from repository
     *
     * @return string
     */
    public static function getTable()
    {
        $Model = static::model();
        return with(new $Model)->getTable();
    }

    /**
     * Get only one record
     *
     * @param  int $id
     * @return null|object
     */
    public function getOne($id)
    {
        $result = $this->model->find($id);
        return $result ? json_decode($result->toJson()) : null;
    }

    /**
     * Get records by conditions
     *
     * @param  array $conditions [
     *  'field1[:operator]' => $value1, // 'id:>' => 100 or 'id:<=' or 'id:>='
     *  'field2' => $value2,            // 'role_id' => 2
     * ]
     * @return array
     */
    public function getBy(array $conditions = [])
    {
        if (empty($conditions)) {
            return [];
        }

        $model = $this->model;
        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $model = $model->whereIn($field, $value);
            } else {
                // Check operator whether it exists in field?
                $operators = explode(':', $field);
                $field = $operators[0];
                $operator = count($operators) > 1 ? $operators[1] : '=';

                $model = $model->where($field, $operator, $value);
            }
        }
        return $model->get();
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
    public function updateBy(array $conditions, array $params)
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
    public function deleteBy($conditions = [])
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
