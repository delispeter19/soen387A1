<?php

class Course {
    private $conn;
    private $table = 'course';

    public $course_code;
    public $course_title;
    public $room_number;
    public $instructor;
    public $days;
    public $course_time;
    public $semester;
    public $start_date;
    public $end_date;

    public $token;

    public function __construct($db){
        $this->conn = $db;
    }

    public function exists(){
        return mysqli_num_rows($this->get_id()) > 0;
    }

    public function post_token(){
        $token = $this->generate_token();
        $user_type = $this->table;

        $query = 'insert into token
            (id, user_id, user_type)
            VALUES (?, ?, ?)';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"sis",
            $token,
            $this->id,
            $user_type
        );

        $result = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        if ($result){
            $this->token = $token;
            return true;
        }

        printf("An error occured: ". mysqli_stmt_error($stmt));        

        return false;
    }

    public function to_json(){
        return array(
            'course_code' => $this->course_code,
            'course_title' => $this->course_title,
            'room_number' => $this->room_number,
            'instructor' => $this->instructor,
            'days' => $this->days,
            'course_time' => $this->course_time,
            'semester' => $this->semester,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        );
    }

    public function get(){
        $query = 'select * from '.$this->table;

        $result = mysqli_query($this->conn, $query);

        return $result;
    }

    public function get_id(){
        $query = "select * from ".$this->table." where course_code = ? limit 1";

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"i",$this->course_code);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);

        return $result;
    }

    public function post(){
        $query = 'insert into '.$this->table.'
            (course_code, course_title, room_number, instructor, days, course_time, semester, start_date, end_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"issssssss",
            $this->course_code,
            $this->course_title,
            $this->room_number,
            $this->instructor,
            $this->days,
            $this->course_time,
            $this->semester,
            $this->start_date,
            $this->end_date,
        );

        $result = mysqli_stmt_execute($stmt);

        $this->course_code = mysqli_insert_id($this->conn);

        mysqli_stmt_close($stmt);

        if ($result){
            return true;
        }

        printf("An error occured: ". mysqli_stmt_error($stmt));        

        return false;

    }

    public function update(){
        $query = 'update '.$this->table.' 
            set 
                course_title = ?,
                room_number = ?,
                instructor = ?,
                days = ?,
                course_time = ?,
                semester = ?,
                start_date = ?,
                end_date = ?
            where course_code = ?';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"ssssssssi",
            $this->course_title,
            $this->room_number,
            $this->instructor,
            $this->days,
            $this->course_time,
            $this->semester,
            $this->start_date,
            $this->end_date,
            $this->course_code
        );

        $result = mysqli_stmt_execute($stmt);

        $affected_rows = mysqli_affected_rows($this->conn);

        mysqli_stmt_close($stmt);

        return array('success' => $result, 'affected_rows' => $affected_rows);

    }

    public function delete(){
        $query = 'delete from '.$this->table.' where course_code = ?';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"i", $this->course_code);

        $result = mysqli_stmt_execute($stmt);

        $affected_rows = mysqli_affected_rows($this->conn);

        mysqli_stmt_close($stmt);

        return array('success' => $result, 'affected_rows' => $affected_rows);
           
    }

    private function generate_token(){
        $string = strval($this->id) . time();

        // Store the cipher method
        $ciphering = "AES-128-CTR";
        
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        
        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567891011121';

        // Store the encryption key
        $encryption_key = "soen387";
        
        // Use openssl_encrypt() function to encrypt the data
        $token = openssl_encrypt($string, $ciphering,
                    $encryption_key, $options, $encryption_iv);

        return $token;
    }
}