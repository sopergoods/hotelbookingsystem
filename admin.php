<?php
include_once 'Dbh.php';

class Admin extends Dbh {
    
    public function findByUsername($username) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        $data = $stmt->get_result()->fetch_assoc();
        
        $stmt->close();
        $conn->close();
        return $data;
    }
}
?>