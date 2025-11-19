<?php
class Dbh {
    private $host;
    private $user;
    private $pwd;
    private $dbName;
    private $port;

    public function __construct() {
        // Railway MySQL sets these specific variables
        if (getenv('MYSQLHOST')) {
            $this->host = getenv('MYSQLHOST');
            $this->user = getenv('MYSQLUSER');
            $this->pwd = getenv('MYSQLPASSWORD');
            $this->dbName = getenv('MYSQLDATABASE');
            $this->port = getenv('MYSQLPORT') ?: 3306;
        } else {
            // Local development settings
            $this->host = "127.0.0.1";
            $this->user = "root";
            $this->pwd = "";
            $this->dbName = "hotel_db";
            $this->port = 3306;
        }
    }

    protected function connect() {
        $conn = new mysqli($this->host, $this->user, $this->pwd, $this->dbName, $this->port);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        return $conn;
    }
}
?>