<?php
class Dbh {
    private $host;
    private $user;
    private $pwd;
    private $dbName;

    public function __construct() {
        // Check if running on Railway (environment variables set)
        if (getenv('MYSQL_HOST')) {
            $this->host = getenv('MYSQL_HOST');
            $this->user = getenv('MYSQL_USER');
            $this->pwd = getenv('MYSQL_PASSWORD');
            $this->dbName = getenv('MYSQL_DATABASE');
        } else {
            // Local development settings
            $this->host = "localhost";
            $this->user = "root";
            $this->pwd = "";
            $this->dbName = "hotel_db";
        }
    }

    protected function connect() {
        $conn = new mysqli($this->host, $this->user, $this->pwd, $this->dbName);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        return $conn;
    }
}
?>