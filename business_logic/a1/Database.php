<?php
class Database {
    private $dbhost = "localhost";
    private $dbuser = "admin387";
    private $dbpass = "test";
    private $dbname = "localschool_db";
    private $conn;

    public function connect(){
        $this->conn = null;
        $this->conn = mysqli_connect($this->dbhost,$this->dbuser,$this->dbpass,$this->dbname);

        if (mysqli_connect_errno()) {
            echo "FAILED TO CONNECT TO MYSQL: ".mysqli_connect_errno();
        }

        return $this->conn;
    }
}