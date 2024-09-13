<?php
class MyModels extends Database {
    private $conn;

    public function __construct() {
        $this->conn = $this->getConnection();
    }

    public function getdata($data = ["*"]) {
        $data = implode(",", $data);
        $sql = "SELECT ".$data." FROM " .$this->table;
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($data) {
        $keys = array_keys($data);
        $params = array_fill(0, count($keys), '?');
        $keys = implode(", ", $keys);
        $params = implode(", ", $params);
        $sql = "INSERT INTO $this->table ($keys) VALUES ($params)";
        $values = array_values($data);
        $query = $this->conn->prepare($sql);
        if ($query->execute($values)) {
            return json_encode(
                array(
                    'type'      => 'success',
                    'Message'   => 'Insert data success',
                    'id'        => $this->conn->lastInsertId()
                )
            );
        } else {
            return json_encode(
                array(
                    'type'      => 'fail',
                    'Message'   => 'Insert data fails',
                )
            );
        }
    }

    public function update($data = NULL, $where = NULL) {
        if ($data != NULL && $where != NULL) {
            $data_sql = $this->buildUpdateString($data);
            $where_sql = $this->buildWhereString($where);
            $sql = "UPDATE $this->table SET $data_sql WHERE $where_sql";
            $query = $this->conn->prepare($sql);
            if ($query->execute(array_merge(array_values($data), array_values($where)))) {
                return json_encode(
                    array(
                        'type'      => 'success',
                        'Message'   => 'Update data success',
                    )
                );
            } else {
                return json_encode(
                    array(
                        'type'      => 'fail',
                        'Message'   => 'Update data fail',
                    )
                );
            }
        }
    }

    public function delete($where = NULL) {
        $where_sql = $this->buildWhereString($where);
        $sql = "DELETE FROM $this->table WHERE $where_sql";
        $query = $this->conn->prepare($sql);
        if ($query->execute(array_values($where))) {
            if ($query->rowCount() > 0) {
                return json_encode(
                    array(
                        'type'      => 'success',
                        'Message'   => 'Delete data success',
                    )
                );
            } else {
                return json_encode(
                    array(
                        'type'      => 'fail',
                        'Message'   => 'No data deleted',
                    )
                );
            }
        } else {
            return json_encode(
                array(
                    'type'      => 'fail',
                    'Message'   => 'Failed to execute query',
                )
            );
        }
    }

    public function deleteById($id) {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(':id', $id);
        if ($query->execute()) {
            return json_encode(
                array(
                    'type'      => 'successfully',
                    'Message'   => 'Delete data success',
                )
            );
        } else {
            return json_encode(
                array(
                    'type'      => 'fail',
                    'Message'   => 'Failed to execute query',
                )
            );
        }
    }

    public function findAll($data = ['*'], $where = NULL, $orderBy = NULL, $order = "DESC") {
        $data = implode(",",$data);
        $where_sql = $this->buildWhereString($where);
        $sql = "SELECT $data FROM $this->table WHERE $where_sql";
        if($orderBy) {
            $sql .= " ORDER BY $orderBy $order";
        }
        $query = $this->conn->prepare($sql);
        $query->execute(array_values($where));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function selectJoin($data = ['*'],
        $limit = NULL,
        $where = NULL,
        $table_join = NULL,
        $query_join = NULL,
        $type_join  = NULL,
        $orderby = NULL,
        $order = 'DESC'
        ) {
        $data = implode(",",$data);
        $sql ="SELECT";
        if($limit) {
            $sql .= " TOP $limit ";
        }
        $sql .= " $data FROM $this->table";
        if (isset($where) && $where != NULL) {
            $fields = array_keys($where);
            $fields_list = implode("",$fields);
            $values = array_values($where);
            $isFields = true;
            if ($table_join != NULL && $query_join != NULL && $type_join != NULL) {
                $sql .= ' '.$this->join_table($this->table,$table_join,$query_join,$type_join).' ';
            }
            $where_sql = $this->buildWhereString($where);
            $sql .= " WHERE ".$where_sql;
            if ($orderby !='' && $orderby != NULL) {
                $sql .= " ORDER BY ".$orderby.' '.$order;
            }
            $query = $this->conn->prepare($sql);
            $query->execute(array_values($where));
        }
        else{
            if ($table_join != NULL && $query_join != NULL && $type_join != NULL) {
                $sql .= ' '.$this->join_table($this->table,$table_join,$query_join,$type_join).' ';
            }
            if ($orderby !='' && $orderby != NULL) {
                $sql .= " ORDER BY ".$orderby.$order;
            }
            $query = $this->conn->prepare($sql);
            $query->execute();
        }
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryExecute($sql) {
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryExecute2($sql) {
        $query = $this->conn->prepare($sql);
        $query->execute();
    }

    private function buildUpdateString($data) {
        $keys_data = array_keys($data);
        $updateArray = [];
        for($i = 0; $i < count($keys_data); $i++) {
            $updateArray[] = "$keys_data[$i] = ?";
        }
        return implode(", ", $updateArray);
    }

    private function buildWhereString($where) {
        $keys = array_keys($where);
        $whereArray = [];
        for($i = 0; $i < count($keys); $i++) {
            $whereArray[] = "$keys[$i] = ?";
        }
        return implode(" AND ", $whereArray);
    }
}
?>