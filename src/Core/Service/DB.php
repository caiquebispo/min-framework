<?php

namespace Caiquebispo\Project\Core\Service;

use PDO;

class DB
{

    private  static ?string $table = null;
    private static string $query = '';
    private static string $partial_query = '';
    private static array $params_query = [];
    private static array $params_query_update = [];
    private static array $conditional_params_query = [];
    private static string $type_query = 'SELECT';
    private static string $type_clause_query = 'WHERE';
    private static array $wheres = [];
    public static $pdo = null;
    public static $instanceOfClass = null;
    public static $drive = 'mysql';
    public static $setting = null;

    private function __construct()
    {
        static::$instanceOfClass = $this;
    }
    /**
     * @param string $table
     * @return DB
     */
    public static function table(string $table_name): ?DB
    {
        self::$table = $table_name;
        return self::getInstanceOfClass();
    }
    /**
     * @param array $params
     * @return DB
     */
    public static function select(array $params = []): DB
    {
        self::$params_query = $params;
        return self::getInstanceOfClass();
    }

    /**
     * @param array $params
     * @return DB
     */
    public  static function where(string|array $column = null, string $operator = null, mixed $value = null): DB
    {
        if (is_array($column)) {
            self::$wheres = $column;
        } else {
            self::$wheres[] = compact('column', 'operator', 'value');
        }

        self::$partial_query .= ' WHERE ';
        $conditions = [];

        foreach (self::$wheres as $where) {
            $conditions[] = (isset($where['column']) && isset($where['operator'])) ? "`{$where['column']}` {$where['operator']} ?" : "`{$where[0]}` {$where[1]} ?";
        }

        self::$partial_query .= implode(' AND ', $conditions);

        return self::getInstanceOfClass();
    }
    /**
     * @return DB
     */
    public static function insert(array $attributes): ?DB
    {
        $isMultidimensional = is_array(reset($attributes));

        if ($isMultidimensional) {

            $columns = array_keys(reset($attributes));

            $values = [];

            foreach ($attributes as $row) {
                $values[] = '(' . implode(',', array_map(fn($value) => self::getInstanceOfPDO()->quote($value), $row)) . ')';
            }

            $placeholders = implode(', ', $values);

            self::$partial_query = "(" . implode(', ', $columns) . ") VALUES $placeholders";
        } else {

            $columns = array_keys($attributes);
            $values = array_values($attributes);
            $placeholders = implode(',', array_map([self::getInstanceOfPDO(), 'quote'], $values));

            self::$partial_query = "(" . implode(', ', $columns) . ") VALUES ($placeholders)";
        }

        self::$params_query = $attributes;

        self::prepareSQLQuery('INSERT');

        return self::getInstanceOfClass();
    }
    /**
     * @return int|null
     */
    public static function id(): ?int
    {
        return self::getInstanceOfPDO()->lastInsertId();
    }
    /**
     * @return bool|\PDOStatement
     */
    private static function createInsertQuery(): bool|\PDOStatement|null
    {
        self::$query = "INSERT INTO " . self::$table . " " . self::$partial_query;
        return self::executeQuery('INSERT');
    }
    /**
     * @param $type_query
     * @return bool|\PDOStatement
     */
    private static function prepareSQLQuery(string $type_query = null): bool|\PDOStatement|null
    {

        return match ($type_query) {
            'INSERT' => self::createInsertQuery(),
        };
    }
    /**
     * @param $type_query
     * @return bool|\PDOStatement
     */
    private static function executeQuery(string $type_query = null): bool|\PDOStatement|null
    {
        if ($type_query  == 'SELECT') {
            return self::getInstanceOfPDO()
                ->query(self::$query);
        }

        return self::getInstanceOfPDO()
            ->prepare(self::$query)
            ->execute();
    }
    /**
     * @return PDO|null
     */
    private static function getInstanceOfPDO(): ?PDO
    {
        if (!self::$pdo) {
            self::$pdo = new PDO('mysql:host=172.17.0.1;dbname=project;user=root;password=password');
        }
        return self::$pdo;
    }
    /**
     * @return DB|null
     */
    private static function getInstanceOfClass(): ?DB
    {
        if (!self::$instanceOfClass) {
            self::$instanceOfClass = new DB();
        }
        return self::$instanceOfClass;
    }
}
