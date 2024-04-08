<?php

namespace App\Kernel\Database;

use App\Kernel\Config\Config;
use PDO;

abstract class Database
{
    protected static ?PDO $pdo = null;
    protected static string $table;
    protected array $fields = [];

    protected array $system = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function __construct()
    {
        $this->fields = array_merge($this->fields, $this->system);

        if (static::$pdo === null) {
            $this->connect();
        }
    }

    private function connect(): void
    {
        $driver = Config::get('database.driver');
        $host = Config::get('database.host');
        $database = Config::get('database.database');
        $charset = Config::get('database.charset');

        static::$pdo = new PDO(
            "$driver:host=$host;dbname=$database;charset=$charset",
            Config::get('database.username'),
            Config::get('database.password')
        );

        static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    // Надо делать класс модели/коллекцию моделей, но времени не хватило на это
    private function setAttributeModel()
    {
        foreach ($this as $key => $value) {
            if (is_string($value) && $this->isJson($value)) {
                $this->$key = json_decode($value, true);
            }
        }
    }

    private function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function get(array $filters = [], array|string $fields = '*')
    {
        if (static::$pdo === null) {
            new static();
        }

        $whereClause = '';
        $bindings = [];

        if (!empty($filters)) {
            $whereParts = [];
            foreach ($filters as $key => $value) {
                $whereParts[] = "$key = :$key";
                $bindings[":$key"] = $value;
            }
            $whereClause = " WHERE " . implode(" AND ", $whereParts);
        }

        if (!is_array($fields)) {
            $fields = [$fields];
        }

        $fieldList = implode(', ', $fields);

        $sql = "SELECT $fieldList FROM " . static::$table . $whereClause;

        try {
            $stmt = static::$pdo->prepare($sql);

            foreach ($bindings as $placeholder => $value) {
                $stmt->bindValue($placeholder, $value);
            }

            $stmt->execute();
            $objects = $stmt->fetchAll(PDO::FETCH_CLASS, get_called_class());

            foreach ($objects as $object) {
                if ($object->setAttributeModel()) {
                    $object->setAttributeModel();
                }
            }

            return $objects;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public static function find($id)
    {
        if (static::$pdo === null) {
            new static();
        }

        $stmt = static::$pdo->prepare("SELECT * FROM " . static::$table . " WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $object = $stmt->fetchObject(get_called_class());

        if ($object) {
            $object->setAttributeModel();
        }

        return $object;
    }

    public static function create(array $data)
    {
        if (static::$pdo === null) {
            new static();
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = json_encode($value);
            }
            if (is_bool($value)) {
                $data[$key] = (int)$value;
            }
        }

        $fields = array_keys($data);
        $columns = implode(', ', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";

        try {
            $stmt = static::$pdo->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }

            $stmt->execute();
            $object = static::find($data['id']);

            if ($object) {
                $object->setAttributeModel();
            }

            return $object;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function update(array $data)
    {
        if (!$this->id) {
            throw new \Exception("Object does not have an ID and cannot be updated.");
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = json_encode($value);
            }
            if (is_bool($value)) {
                $data[$key] = (int)$value;
            }
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = "$key = :$key";
        }
        $setClause = implode(', ', $setParts);

        $sql = "UPDATE " . static::$table . " SET $setClause WHERE id = :id";

        if (static::$pdo === null) {
            new static();
        }

        try {
            $stmt = static::$pdo->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindValue(':id', $this->id);

            $stmt->execute();

            $object = static::find($this->id);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
