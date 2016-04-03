<?php

namespace Model\Base;
use Base\Model;
use InternalException;

/**
 * Base domain class
 *
 * @throws InternalException
 */
abstract class Domain extends Model {

    const TABLE = null;

    protected $id = 0;
    protected $properties = null;

    private $created = false;

    public function create(array $properties) {
        if (!is_array($this->properties)) {
            throw new InternalException('Properties of class ' . get_class($this) . ' are not set');
        }
        foreach ($this->properties as $key => $v) {
            if (isset($properties[$key])) {
                $this->set($key, $properties[$key]);
            } else {
                throw new InternalException('Properties given don\'t have the same keys as properties of ' . get_class($this));
            }
        }
        $this->created = true;
    }

    public function fromId($id) {
        return $this->fromProperty('id', intval($id));
    }

    public function fromProperty($key, $value) {
        return $this->from(function ($search) use ($key, $value) {
            $search->where($key, $value);
        });
    }

    protected function from(callable $search) {
        $stmt = $this->database->select()->from($this->getTable());
        // Use callback to populate where
        $search($stmt);
        // It must be unique
        $rows = $stmt->take(2)->all();

        if (count($rows) === 1) {
            return $this->fromEntry($rows[0]);
        }

        return false;
    }

    protected function find(callable $search) {
        $stmt = $this->database->select()->from($this->getTable());
        $search($stmt);

        $rows = $stmt->all();
        $coll = [];

        foreach ($rows as $row) {
            $obj = $this->new();
            if ($obj->fromEntry($row)) {
                $coll[] = $obj;
            }
        }

        return $coll;
    }

    public function fromEntry(\stdClass $row) {
        $id = intval($row->id);
        unset($row->id);
        foreach ($this->properties as $key => $v) {
            if (isset($row->$key)) {
                $this->set($key, $row->$key);
            } else {
                throw new InternalException('Properties given don\'t have the same keys as properties of ' . get_class($this));
            }
        }
        $this->id = $id;
        return true;
    }

    public function exists() {
        return $this->id > 0;
    }

    public function set($key, $value = null) {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            $method = '__set' . ucfirst(snakeToCamel($key));
            // $this->__setPascalCase();
            if (method_exists($this, $method)) {
                $newValue = $this->$method($value);
                $this->properties[$key] = $newValue ?? $value;
            } else {
                throw new InternalException('Setter is not defined for ' . $key . ' of class ' . get_class($this) . '.');
            }
        }
    }

    public function __call($method, $args) {
        $key = camelToSnake(substr($method, 3));
        if (stripos($method, 'set') === 0 and strlen($method) > 3) {
            // $this->__setPascalCase();
            if (count($args) === 1) {
                $method = '__set' . substr($method, 3);
                if (method_exists($this, $method)) {
                    $newValue = $this->$method($args[0]);
                    $this->properties[$key] = $newValue ?? $args[0];
                } else {
                    throw new InternalException('Setter is not defined for ' . $key . ' of class ' . get_class($this) . '.');
                }
            } else {
                throw new InternalException('The magic setter expects to have 1 parameter, ' . count($args) . ' given.');
            }
        } else if (stripos($method, 'get') === 0 and strlen($method) > 3) {
            // $this->__setPascalCase();
            if (count($args) === 0) {
                if (isset($this->properties[$key])) {
                    return $this->properties[$key];
                } else {
                    throw new InternalException(sprintf('Call to undefined function: %s::%s().', get_class($this), $method));
                }
            } else {
                throw new InternalException('The magic getter expects to have 0 parameters, ' . count($args) . ' given.');
            }
        } else {
            throw new InternalException(sprintf('Call to undefined function: %s::%s().', get_class($this), $method));
        }
    }

    public function equals($other) {
        if ($other instanceof Domain and get_class($this) === get_class($other)) {
            return $this->getId() === $other->getId();
        } else if (is_int($other) or ctype_digit($other)) {
            return $this->getId() === intval($other);
        } else {
            throw new InternalException('Can\'t compare that type of data');
        }
    }

    public function getId() {
        return intval($this->id);
    }

    public function get($key) {
        return isset($this->properties[$key]) ? $this->properties[$key] : null;
    }

    public function save() {
        if ($this->exists()) {
            $this->database->update($this->properties)->where('id', $this->getId())->into($this->getTable());
        } else if ($this->created) {
            $this->database->insert($this->properties)->into($this->getTable());
            $this->id = $this->database->lastInsertId();
        } else {
            throw new InternalException('Save called but object don\'t exists or was not created');
        }
    }

    public function delete() {
        if ($this->exists()) {
            $this->onDelete();
            $this->database->delete($this->getTable())->where('id', $this->getId())->confirm();
            $this->id = 0;
        }
    }

    protected function getTable() {
        if (!empty(static::TABLE)) {
            return static::TABLE;
        }

        $class = get_class_name($this);
        if (lcfirst($class) === strtolower($class)) {
            return strtolower($class) . 's';
        }

        throw new InternalException('Don\'t know in which table "' .$class . '" should be stored');
    }

    protected function arrayHasKeys(array $required, array $test) {        
        foreach ($required as $key => $value) {
            if (!isset($test[$key])) {
                return false;
            }
        }
        return true;
    }

    protected function onDelete() {}

}
