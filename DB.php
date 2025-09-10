<?php
$db = new DB();
class DB
{
    public $data = [];
    public $array = [];
    private $limit = null; // Class başına eklenmeli
    
    public function Limit($limit)
    {
        if (is_numeric($limit)) {
            $this->limit = intval($limit);
        }
        return $this;
    }

    public function Get($Table, $Status = null)
    {
        global $conn;
        
        $query = "SELECT * FROM $Table";
        
        if ($this->limit !== null) {
            $query .= " LIMIT " . $this->limit;
            $this->limit = null; // Her çağrıda sıfırlıyoruz
        }
    
        $sql = $conn->prepare($query);
        $sql->execute();
        $value = $sql->fetchAll(PDO::FETCH_ASSOC);
        $this->data = $value;
        $data = $value;
    
        if ($Status != null) {
            return $data;
        } else {
            return $this;
        }
    }

    public function OrderBy($Column, $Direction = "ASC")
    {
        usort($this->data, function ($a, $b) use ($Column, $Direction) {
            if ($Direction == "ASC") {
                return $a[$Column] <=> $b[$Column];
            } else {
                return $b[$Column] <=> $a[$Column];
            }
        });
        return $this;
    }

    public function Where($Column, $Where, $Result = null)
    {
        $this->array = [];
        if (isset($Result)) {
            foreach ($this->data as $key) {
                if ($key[$Column] == $Where) {
                    return $key[$Result];
                }
            }
        } else {
            foreach ($this->data as $key) {
                if ($key[$Column] == $Where) {
                    $array[] = $key;
                    $this->array[] = $key;
                }
            }
            if (isset($array)) {
                return $array;
            }
        }
        return $this;
    }
    public function Insert($Table, $Values)
    {
        global $conn;
        foreach ($Values as $key => $value) {
            $Values[$key] = mb_convert_encoding($value, 'UTF-8', 'auto');
        }
        $columns = implode(', ', array_keys($Values));
        $placeholders = implode(', ', array_fill(0, count($Values), '?'));
        $sql = $conn->prepare("INSERT INTO $Table ($columns) VALUES ($placeholders)");
        $sql->execute(array_values($Values));
        return $conn->lastInsertId();
    }
    public function Update($Table, $Values, $Where)
    {
        global $conn;

        $setPart = [];
        foreach ($Values as $column => $value) {
            $setPart[] = "$column = ?";
        }
        $setPartString = implode(', ', $setPart);

        $wherePart = [];
        foreach ($Where as $column => $value) {
            $wherePart[] = "$column = ?";
        }
        $wherePartString = implode(' AND ', $wherePart);

        $sql = "UPDATE $Table SET $setPartString WHERE $wherePartString";
        $stmt = $conn->prepare($sql);

        $params = array_merge(array_values($Values), array_values($Where));
        $result = $stmt->execute($params);

        return $result;
    }
    public function Delete($Table, $Where)
    {
        global $conn;

        $wherePart = [];
        foreach ($Where as $column => $value) {
            $wherePart[] = "$column = ?";
        }
        $wherePartString = implode(' AND ', $wherePart);

        $sql = "DELETE FROM $Table WHERE $wherePartString";
        $stmt = $conn->prepare($sql);

        $result = $stmt->execute(array_values($Where));

        return $result;
    }
    public function Row($array)
    {
        if (is_array($array) || $array instanceof Countable) {
            if (count($array) > 0) {
                return count($array);
            }
        } else {
            return 0;
        }
    }
}
