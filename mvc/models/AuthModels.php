<?php
class AuthModels extends Database {
    protected $table = "Users";

    public function __construct() {
        $this->conn = $this->getConnection();
    }
    
    public function login($data) {
        $kq = $this->findUsername($data['username']);
        if($kq) {
            if (password_verify($data['password'], $kq['password'])) {
                if($kq['role_id'] == "1") {
                    $role = 'admin';
                } else if($kq['role_id'] == 2) {
                   $role = 'user';
                }
                return json_encode(
                    array(
                        'type'  => 'success',
                        'id'    => $kq['id'],
                        'role'  => $role,
                        'data'  => $kq,
                    )
                );
            }
            return json_encode(
                array(
                    'type'  => 'fail',
                )
            );
        }
        return json_encode(
            array(
                'type'  => 'fail',
            )
        );
    }

    public function register($data) {
        $existingUser = $this->findUsername($data['username']);
        if ($existingUser) {
            return json_encode(
                array(
                    'type'      => 'fail',
                    'Message'   => 'User already exists',
                )
            );
        }
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
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
                    'err'       => 'Sever error'
                )
            );
        }
    }
    
    private function findUsername($username) {
        $sql = "SELECT * FROM [$this->table] WHERE username = :username";
        $query = $this->conn->prepare($sql);
        $query->bindParam(':username', $username);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
?>