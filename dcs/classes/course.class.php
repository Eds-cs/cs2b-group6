<?php

require_once 'database.class.php';
require_once 'app.class.php';

class Course
{
    public $course_id;
    public $course_name;
    public $yr_level;
    public $teacher_id;
    public $assigned_professor;

    private $db;

    function __construct()
    {
        $this->db = Database::getInstance(); // Create a new instance of the Database class
        $this->application = Application::getInstance();
    }

    function showCourses($keyword = '')
    {
        $sql = "SELECT c.*,CONCAT(a.first_name,' ',a.middle_initial,' ', a.last_name) as assigned_professor
                FROM course c
                INNER JOIN teacher_profiles tp ON c.teacher_id = tp.teacher_id
                INNER JOIN accounts a ON tp.account_id = a.account_id
                WHERE course_name LIKE '%' :keyword '%'
                ORDER BY course_name ASC ";

        $qry = $this->db->connection()->prepare($sql);
        $qry->bindParam(":keyword", $keyword);

        $data = [];
        if ($qry->execute()) {
            $data = $qry->fetchAll();
        }
        return $data;

    }

    function showTeachers(){
        $sql = "SELECT DISTINCT CONCAT(a.first_name, ' ', a.middle_initial, ' ', a.last_name) AS assigned_professor, tp.teacher_id
                FROM teacher_profiles tp
                INNER JOIN accounts a ON tp.account_id = a.account_id;";
        $qry = $this->db->connection()->prepare($sql);

        $data = [];
        if ($qry->execute()) {
            $data = $qry->fetchAll();
        }
        return $data;
    }

    function editCourse($teacher_id,$course_id)
    {
        $sql = "UPDATE course 
                SET teacher_id = :teacher_id
                WHERE course_id = :course_id";
        $qry = $this->db->connection()->prepare($sql);
        $qry->bindParam(":teacher_id", $teacher_id);
        $qry->bindParam(":course_id", $course_id);
        return $qry->execute();
    }

    function fetchCourse($recordID){
        $sql = "SELECT * 
                FROM course
                WHERE course_id = :recordID";
        $qry = $this->db->connection()->prepare($sql);
        $qry->bindParam(":recordID",$recordID);
        $data = [];
        if($qry->fetch()){
            $data = $qry->fetch();
        }
        return $data;
    }

}

?>