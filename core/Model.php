<?php
abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = ['password'];

    public function __construct($db) {
        $this->db = $db;
        
        if (empty($this->table)) {
            // Get the model class name without 'Model' suffix and pluralize it
            $className = get_class($this);
            $tableName = strtolower(str_replace('Model', '', $className));
            $this->table = $tableName . 's';
        }
    }

    public function find($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            return $this->processResult($stmt->fetch());
        } catch (PDOException $e) {
            Logger::error("Error finding record in {$this->table}", ['error' => $e->getMessage()]);
            throw new Exception("Error finding record");
        }
    }

    public function findBy($field, $value) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$field} = :value");
            $stmt->execute(['value' => $value]);
            return $this->processResult($stmt->fetch());
        } catch (PDOException $e) {
            Logger::error("Error finding record by {$field} in {$this->table}", ['error' => $e->getMessage()]);
            throw new Exception("Error finding record");
        }
    }

    public function all() {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table}");
            return array_map([$this, 'processResult'], $stmt->fetchAll());
        } catch (PDOException $e) {
            Logger::error("Error fetching all records from {$this->table}", ['error' => $e->getMessage()]);
            throw new Exception("Error fetching records");
        }
    }

    public function create(array $data) {
        $data = $this->filterFillable($data);
        
        try {
            $fields = array_keys($data);
            $placeholders = array_map(function($field) {
                return ":{$field}";
            }, $fields);

            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                $this->table,
                implode(', ', $fields),
                implode(', ', $placeholders)
            );

            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);

            return $this->find($this->db->lastInsertId());
        } catch (PDOException $e) {
            Logger::error("Error creating record in {$this->table}", ['error' => $e->getMessage()]);
            throw new Exception("Error creating record");
        }
    }

    public function update($id, array $data) {
        $data = $this->filterFillable($data);
        
        try {
            $fields = array_map(function($field) {
                return "{$field} = :{$field}";
            }, array_keys($data));

            $sql = sprintf(
                "UPDATE %s SET %s WHERE %s = :id",
                $this->table,
                implode(', ', $fields),
                $this->primaryKey
            );

            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_merge($data, ['id' => $id]));

            return $this->find($id);
        } catch (PDOException $e) {
            Logger::error("Error updating record in {$this->table}", ['error' => $e->getMessage()]);
            throw new Exception("Error updating record");
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            Logger::error("Error deleting record from {$this->table}", ['error' => $e->getMessage()]);
            throw new Exception("Error deleting record");
        }
    }

    public function where($conditions, $params = []) {
        try {
            $whereClause = implode(' AND ', array_map(function($field) {
                return "{$field} = :{$field}";
            }, array_keys($conditions)));

            $sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($conditions);

            return array_map([$this, 'processResult'], $stmt->fetchAll());
        } catch (PDOException $e) {
            Logger::error("Error querying {$this->table}", ['error' => $e->getMessage()]);
            throw new Exception("Error querying records");
        }
    }

    protected function filterFillable(array $data) {
        return array_intersect_key($data, array_flip($this->fillable));
    }

    protected function processResult($result) {
        if (!$result) {
            return null;
        }

        foreach ($this->hidden as $field) {
            unset($result[$field]);
        }

        return $result;
    }

    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollback() {
        return $this->db->rollBack();
    }

    protected function cache($key, $callback, $ttl = null) {
        $cache = Cache::getInstance();
        return $cache->remember($key, $callback, $ttl);
    }
}
