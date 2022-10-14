<?php

class Administrator {
    private $conn;
    private $table = 'administrator';

    public $employment_id;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $address;
    public $date_of_birth;

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
            $this->employment_id,
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
                'employment_id' => $this->employment_id,
                'email' => $this->email,
                'password' => $this->password,
                'address' => $this->address,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone_number' => $this->phone_number,
                'date_of_birth' => $this->date_of_birth,
            );
    }

    public function login(){
        $query = 'select * from '.$this->table.' where email = ? limit 1';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"s",
            $this->email
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);

        if($result && mysqli_num_rows($result) > 0){

            $admin_data = mysqli_fetch_assoc($result);
            
            if($admin_data['password'] === $this->password)
            {
                $this->employment_id = (int)$admin_data['employment_ID'];
                $this->email = $admin_data['email'];
                $this->password = $admin_data['password'];
                $this->address = $admin_data['address'];
                $this->first_name = $admin_data['first_name'];
                $this->last_name = $admin_data['last_name'];
                $this->phone_number = $admin_data['phone_number'];
                $this->date_of_birth = $admin_data['date_of_birth'];

                return true;
            }
        }       

        return false;
    }

    public function get(){
        $query = 'select * from '.$this->table;

        $result = mysqli_query($this->conn, $query);

        return $result;
    }

    public function get_id(){
        $query = "select * from ".$this->table." where employment_id = ? limit 1";

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"i",$this->employment_id);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);

        return $result;         
    }

    public function post(){
        $query = 'insert into '.$this->table.'
            (employment_id, email, password, address, first_name, last_name, phone_number, date_of_birth)
            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"sssssss",
            $this->email,
            $this->password,
            $this->address,
            $this->first_name,
            $this->last_name,
            $this->phone_number,
            $this->date_of_birth
        );

        $result = mysqli_stmt_execute($stmt);

        $this->employment_id = mysqli_insert_id($this->conn);
        
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
                email = ?,
                password = ?,
                address = ?,
                first_name = ?,
                last_name = ?,
                phone_number = ?,
                date_of_birth = ?
            where employment_id = ?';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"sssssssi",
            $this->email,
            $this->password,
            $this->address,
            $this->first_name,
            $this->last_name,
            $this->phone_number,
            $this->date_of_birth,
            $this->employment_id
        );

        $result = mysqli_stmt_execute($stmt);

        $affected_rows = mysqli_affected_rows($this->conn);

        mysqli_stmt_close($stmt);

        return array('success' => $result, 'affected_rows' => $affected_rows);

    }

    public function delete(){
        $query = 'delete from '.$this->table.' where employment_id = ?';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"i", $this->employment_id);

        $result = mysqli_stmt_execute($stmt);

        $affected_rows = mysqli_affected_rows($this->conn);

        mysqli_stmt_close($stmt);

        return array('success' => $result, 'affected_rows' => $affected_rows);
           
    }

    private function generate_token(){
        $string = strval($this->employment_id) . time();

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