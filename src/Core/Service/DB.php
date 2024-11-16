<?php

namespace Caiquebispo\Project\Core\Service;

use PDO;

class DB
{

    private  static ?string $table = null;
    private static string $query = '';
    private static string $partial_query = '';
    private static string $params_query = ' * ';
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
    public static function select(mixed $params = ' * '): DB
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
        self::$partial_query = '';

        if (is_array($column)) {
            self::$wheres = $column;
        } else {
            self::$wheres[] = compact('column', 'operator', 'value');
        }

        self::$partial_query .= ' WHERE ';

        $conditions = [];

        foreach (self::$wheres as $where) {

            $conditions[] = (isset($where['column']) && isset($where['operator'])) ? "`{$where['column']}` {$where['operator']} " . self::getInstanceOfPDO()->quote($where['value']) : "`{$where[0]}` {$where[1]} " . self::getInstanceOfPDO()->quote($where[2]);
        }

        self::$partial_query .= implode(' AND ', $conditions);

        return self::getInstanceOfClass();
    }
    /**
     * @param array $params
     * @return DB
     */
    public static function whereIn(string $field, array $attributes): DB
    {

        self::$partial_query .= "WHERE {$field} IN (" . implode(',', $attributes) . ")";

        self::prepareSQLQuery('SELECT');

        return self::getInstanceOfClass();
    }
    /**
     * @param array $params
     * @return DB
     */
    public static function whereNotIn(string $field, array $attributes): DB
    {

        self::$partial_query .= "WHERE {$field} NOT IN (" . implode(',', $attributes) . ")";

        self::prepareSQLQuery('SELECT');

        return self::getInstanceOfClass();
    }
    /**
     * @param array $params
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

        //self::$params_query = $attributes;

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
     * @return bool|array
     */
    public static function get(): bool|array
    {
        return self::prepareSQLQuery('SELECT')->fetchAll();
    }
    public static function delete(): ?bool
    {
        return self::prepareSQLQuery('DELETE');
    }
    /**
     * @return mixed
     */
    public static function first(): mixed
    {
        return self::prepareSQLQuery('SELECT')->fetch();
    }
    /**
     * @return bool|\PDOStatement
     */
    private static function createInsertQuery(): bool|\PDOStatement|null
    {
        self::$query = "INSERT INTO " . self::$table . " " . self::$partial_query;
        return self::executeQuery('INSERT');
    }
    private static function createSelectQuery()
    {
        self::$query = "SELECT " . self::$params_query . " FROM " . self::$table . " " . self::$partial_query;

        return self::executeQuery('SELECT');
    }
    private static function createDeleteQuery()
    {
        self::$query = "DELETE FROM " . self::$table . " " . self::$partial_query;

        return self::executeQuery();
    }
    /**
     * @param $type_query
     * @return bool|\PDOStatement
     */
    private static function prepareSQLQuery(string $type_query = null): bool|\PDOStatement|null
    {

        return match ($type_query) {
            'INSERT' => self::createInsertQuery(),
            'SELECT' => self::createSelectQuery(),
            'DELETE' => self::createDeleteQuery(),
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
