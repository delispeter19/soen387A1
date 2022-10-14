<?php

class StudentCourse {
    private $conn;
    private $table = 'student_course';

    public $student_id;
    public $course_code;

    public function __construct($db){
        $this->conn = $db;
    }

    public function to_json(){
        return array(
            'student_id' => $this->student_id,
            'course_code' => $this->course_code
        );
    }

    public function get(){
        $query = 'select * from '.$this->table;

        $result = mysqli_query($this->conn, $query);

        return $result;
    }

    public function get_id(){
        $query = "select * from ".$this->table." where student_id = ? and course_code = ? limit 1";

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"ii",
            $this->student_id,
            $this->course_code
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);

        return $result;
    }

    public function get_course_id($course){
        $course->course_code = $this->course_code;

        $result = $course->get_id();
        
        $row = mysqli_fetch_assoc($result);
        
        extract($row); 

        $course->course_code = (int)$course_code;
        $course->course_title = $course_title;
        $course->room_number = $room_number;
        $course->instructor = $instructor;
        $course->days = $days;
        $course->course_time = $course_time;
        $course->semester = $semester;
        $course->start_date = $start_date;
        $course->end_date = $end_date;

        return $course->to_json();
    }

    public function get_students(){
        $query = "select * from student as s, ".$this->table." as sc where s.ID = sc.student_id and sc.course_code = ?";

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"i",
            $this->course_code
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);

        return $result;
    }

    public function get_courses(){
        $query = "select * from course as c, ".$this->table." as sc where c.course_code = sc.course_code and sc.student_id = ?";

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"i",
            $this->student_id
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        
        mysqli_stmt_close($stmt);

        return $result;
    }

    public function post_verify(){
        if ($this->is_less_than_5_courses() and $this->is_less_than_1_week_after_start()){
            return $this->post();
        } else {
            return false;
        }
    }

    private function post(){
        $query = 'insert into '.$this->table.'
            (student_id, course_code)
            VALUES (?, ?)';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"ii",
            $this->student_id,
            $this->course_code
        );

        $result = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        if ($result){
            return true;
        }

        printf("An error occured: ". mysqli_stmt_error($stmt));        

        return false;

    }

    public function delete_verify(){
        $end_date = strtotime($this->get_end_date());

        $now = time();
        
        $date_diff = $now-$end_date;

        // if less than end of semester
        if (round($date_diff / (60 * 60 * 24)) < 0){
            return $this->delete();
        } else {
            return false;
        }
    }

    private function delete(){
        $query = 'delete from '.$this->table.' where student_id = ? and course_code = ?';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"ii",
            $this->student_id,
            $this->course_code
        );

        $result = mysqli_stmt_execute($stmt);

        $affected_rows = mysqli_affected_rows($this->conn);

        mysqli_stmt_close($stmt);

        return array('success' => $result, 'affected_rows' => $affected_rows);
           
    }

    private function is_less_than_1_week_after_start(){
        $start_date = strtotime($this->get_start_date());

        $now = time();
        
        $date_diff = $now-$start_date;

        return round($date_diff / (60 * 60 * 24)) <= 7;
    }

    private function is_less_than_5_courses(){
        $semester = $this->get_semester();

        $query = 'select count(*) from course as c,
        '.$this->table.' as sc 
        where c.semester = ?
        and c.course_code = sc.course_code 
        and sc.student_id = ?';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"si",
            $semester,
            $this->student_id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result(
            $stmt, 
            $num_courses_for_semester
        );

        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);

        return $num_courses_for_semester < 5;
    }

    private function get_semester(){
        $query = 'select semester from course where course_code = ?';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"i",
            $this->course_code
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result(
            $stmt, 
            $semester
        );

        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);

        return $semester;
    }

    private function get_start_date(){
        $query = 'select start_date from course where course_code = ?';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"i",
            $this->course_code
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result(
            $stmt, 
            $start_date
        );

        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);

        return $start_date;
    }

    private function get_end_date(){
        $query = 'select end_date from course where course_code = ?';

        $stmt = mysqli_prepare($this->conn, $query);

        mysqli_stmt_bind_param($stmt,"i",
            $this->course_code
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result(
            $stmt, 
            $end_date
        );

        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);

        return $end_date;
    }
}