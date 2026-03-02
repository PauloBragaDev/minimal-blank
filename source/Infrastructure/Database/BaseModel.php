<?php

namespace Source\Infrastructure\Database;

use Source\Infrastructure\View\Message;

/**
 * Yelloweb | Class BaseModel
 *
 * @author Paulo Braga <tecnologia@yelloweb.com.br>
 * @package Source\Infrastructure\Database
 */
abstract class BaseModel
{
    /** @var object|null */
    protected $data;

    /** @var \PDOException|null */
    protected $fail;

    /** @var Message|null */
    protected $message;

    /** @var string */
    protected $query;

    /** @var string */
    protected $params;

     /** @var string */
     protected $group;

    /** @var string */
    protected $order;

    /** @var int */
    protected $limit;

    /** @var int */
    protected $offset;

    /** @var string $entity database table */
    protected $entity;

    /** @var array $protected no update or create */
    protected $protected;

    /** @var array $entity database table */
    protected $required;

    /**
     * Model constructor.
     * @param string $entity database table name
     * @param array $protected table protected columns
     * @param array $required table required columns
     */
    public function __construct(string $entity, array $protected, array $required)
    {
        $this->entity = $entity;
        $this->protected = array_merge($protected, ['created_at', "updated_at"]);
        $this->required = $required;

        $this->message = new Message();
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }

        $this->data->$name = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    /**
     * @return null|object
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * @return \PDOException
     */
    public function fail(): ?\PDOException
    {
        return $this->fail;
    }

    /**
     * @return Message|null
     */
    public function message(): ?Message
    {
        return $this->message;
    }

    /**
     * @param null|string $terms
     * @param null|string $params
     * @param string $columns
     * @return Model|mixed
     */
    public function select(?string $terms = null, ?string $params = null, string $columns = "*", $mostra = false)
    {
        if ($terms) {
            $this->query = "SELECT {$columns} FROM " . $this->entity . " WHERE {$terms}";
            if($mostra){
            echo "=SELECT {$columns} FROM " . $this->entity . " WHERE {$terms}";exit;

            }
            parse_str($params, $this->params);
            return $this;
        }

        $this->query = "SELECT {$columns} FROM " . $this->entity;
        return $this;
    }
    
    public function pureSelect($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param null|string $terms
     * @param null|string $params
     * @param string $columns
     * @return Model|mixed
     */
    public function join(?string $terms = null, ?string $params = null, string $join, string $columns = "*")
    {
        $this->query = "SELECT {$columns} FROM " . $this->entity . " ".$join . " WHERE {$terms}";
        parse_str($params, $this->params);
        return $this;
    }

    /**
     * @param string $columnGroup
     * @return Model
     */
    public function group(string $columnGroup): Model
    {
        $this->group = " GROUP BY {$columnGroup}";
        return $this;
    }

    /**
     * @param string $columnOrder painel_controle_epi
     * @return Model
     */
    public function order(string $columnOrder): Model
    {
        $this->order = " ORDER BY {$columnOrder}";
        return $this;
    }

    /**
     * @param int $limit
     * @return Model
     */
    public function limit(int $limit): Model
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    /**
     * @param int $offset
     * @return Model
     */
    public function offset(int $offset): Model
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    /**
     * @param bool $all
     * @return null|array|mixed|static
     */
    public function fetch(bool $all = false): ?static
    {
        try {
            $stmt = DatabaseConnection::getInstance()->prepare($this->query . $this->group . $this->order . $this->limit . $this->offset);

            $stmt->execute($this->params);
            
            if (!$stmt->rowCount()) {
                return null;
            }

            if ($all) {
                return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
            }

            return $stmt->fetchObject(static::class);
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $key
     * @return int
     */
    public function count(string $key = "id"): int
    {
        $stmt = DatabaseConnection::getInstance()->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->rowCount();
    }

    /**
     * @param int $id
     * @param string|null $columns
     * @return null|mixed|static
     */
    public function findById(int $id, string $columns = "*"): ?static
    {
        return $this->select("id = :id", "id={$id}", $columns)->fetch();
    }

    /**
     * @param array $data
     * @return int|null
     */
    protected function insert(array $data): ?int
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            
            $stmt = DatabaseConnection::getInstance()->prepare("INSERT INTO " . $this->entity . " ({$columns}) VALUES ({$values})");
            $stmt->execute($this->filter($data));

            return DatabaseConnection::getInstance()->lastInsertId();
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param array $data
     * @param string $terms
     * @param string $params
     * @return int|null
     */
    protected function update(array $data, string $terms, string $params): ?int
    {
        try {
            $dateSet = [];
            foreach ($data as $bind => $value) {
                $dateSet[] = "{$bind} = :{$bind}";
            }
            $dateSet = implode(", ", $dateSet);
            parse_str($params, $params);

            $stmt = DatabaseConnection::getInstance()->prepare("UPDATE " . $this->entity . " SET {$dateSet} WHERE {$terms}");
            $stmt->execute($this->filter(array_merge($data, $params)));
            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $terms
     * @param null|string $params
     * @return bool
     */
    public function delete(string $terms, ?string $params): bool
    {
        try {
            $stmt = DatabaseConnection::getInstance()->prepare("DELETE FROM " . $this->entity . " WHERE {$terms}");
            if ($params) {
                parse_str($params, $params);
                $stmt->execute($params);
                return true;
            }

            $stmt->execute();
            return true;
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return false;
        }
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->required()) {
            $this->message->warning($this->fail());
            return false;
        }

        /** Update */
        if (!empty($this->id)) {
            $id = $this->id;
            $this->update($this->safe(), "id = :id", "id={$id}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados err: ".$this->fail());
                return false;
            }
        }

        /** Create */
        if (empty($this->id)) {
            $id = $this->insert($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados : ".$this->fail());
                return false;
            }
        }
        $this->data = $this->findById($id)->data();
        return true;
    }

    /**
     * @return array|null
     */
    protected function safe(): ?array
    {
        $safe = (array)$this->data;
        foreach ($this->protected as $unset) {
            unset($safe[$unset]);
        }
        return $safe;
    }

    /**
     * @param array $data
     * @return array|null
     */
    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }
        return $filter;
    }

    /**
     * @return bool
     */
    protected function required(): bool
    {
        $data = (array)$this->data();
        foreach ($this->required as $field) {
            if (empty($data[$field])) {
                $this->fail = "{$data[$field]}";
                return false;
            }
        }
        return true;
    }
}