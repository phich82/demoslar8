<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class DBService
{
    /**
     * Begin transaction
     *
     * @return void
     */
    public static function beginTransaction()
    {
        DB::beginTransaction();
    }

    /**
     * Commit database
     *
     * @return void
     */
    public static function commit()
    {
        DB::commit();
    }

    /**
     * Rollback database
     *
     * @return void
     */
    public static function rollback()
    {
        DB::rollBack();
    }

    /**
     * Begin transaction automatically
     *
     * @param  \Closure $callback
     * @param  int $attempsDeadLock
     * @return mixed
     */
    public static function transaction(callable $callback, $attempsDeadLock = 1)
    {
        return DB::transaction($callback, $attempsDeadLock);
    }

    /**
     * Add a query `select count(*)` for pagination
     *
     * @param  string|array $sql [array: [$selectSql, $fromSql], string: $selectFromSql]
     * @return string
     */
    public static function withPaginationSql($sql)
    {
        if ((!is_string($sql) && !is_array($sql)) || empty($sql)) {
            throw new Exception("It must not be an empty string or array.");
        }
        if (is_string($sql)) {
            return "SELECT COUNT(*) FROM ({$sql}) AS count";
        }
        $selectSql = $sql[0];
        if (count($sql) === 1) {
            return "SELECT COUNT(*) FROM ({$selectSql}) AS count";
        }
        $fromSql = $sql[1];
        return "SELECT COUNT(*) AS count {$fromSql}";
    }

    /**
     * Get total for pagination
     *
     * @param  string|array $prepareSql [array: [$selectSql, $fromSql], string: $selectFromSql]
     * @param  array $prepareBinding
     * @return int
     */
    public static function getPaginationTotal($prepareSql, array $prepareBinding = [])
    {
        $result = self::select(self::withPaginationSql($prepareSql), $prepareBinding);
        return empty($result) ? 0 : $result[0]->count;
    }

    /**
     * Get record list by query
     *
     * @param  string $sql
     * @param  array $binding
     * @param  bool $useReadPdo
     * @return array
     */
    public static function select($sql, array $binding = [], $useReadPdo = true)
    {
        return DB::select($sql, $binding, $useReadPdo);
    }

    /**
     * Paginate
     *
     * @param  string $sql
     * @param  array $binding
     * @param  int $page
     * @param  int $perPage
     * @param  array $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginate($sql, array $binding = [], $page = null, $perPage = 10, $options = [])
    {
        // Get total from sql
        $total = self::getPaginationTotal($sql, $binding);
        // Resolve current page
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        // Append limit, offset to sql
        $offset = ($page < 1 ?  0 : $page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        // Get items
        $items = self::select($sql, $binding);
        // Keep all old query parameters from the url
        if (!isset($options['path'])) {
            $options['path'] = request()->url();
        }
        if (isset($options['query'])) {
            $options['query'] = request()->query();
        }
        // Set default view of pagination
        LengthAwarePaginator::$defaultView = 'pagination.index';

        return new LengthAwarePaginator($items, $total, $perPage, $page, $options);
    }
}
