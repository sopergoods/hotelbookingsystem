<?php

include_once 'Dbh.php';

class Admin extends Dbh {
    
    public $id;
    public $username;
    public $password_hash;
    
    /**
     * Find admin by username
     * @param string $username
     * @return array|null - Returns admin data or null if not found
     */
    public function findByUsername($username) {
        $conn = $this->connect();
        
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        
        $stmt->close();
        $conn->close();
        
        return $admin;
    }
    
    /**
     * Find admin by ID
     * @param int $id
     * @return array|null
     */
    public function find($id) {
        $conn = $this->connect();
        
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        
        $stmt->close();
        $conn->close();
        
        return $admin;
    }
    
    /**
     * Create new admin
     * @return bool
     */
    public function create() {
        $conn = $this->connect();
        
        // Hash password
        $hashed_password = password_hash($this->password_hash, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
        $stmt->bind_param("ss", $this->username, $hashed_password);
        $result = $stmt->execute();
        
        $stmt->close();
        $conn->close();
        
        return $result;
    }
    
    /**
     * Get all admins
     * @return array
     */
    public function all() {
        $conn = $this->connect();
        
        $result = $conn->query("SELECT id, username FROM admin_users ORDER BY id ASC");
        $admins = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $admins[] = $row;
            }
        }
        
        $conn->close();
        return $admins;
    }
}

?>