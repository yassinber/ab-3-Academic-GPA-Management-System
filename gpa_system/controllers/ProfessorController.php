<?php
require_once 'models/Semester.php';
require_once 'models/Assignment.php';

class ProfessorController {
    public function handle($page) {
        if ($page === 'professor.grades') {
            // جلب الفصول التي تحتوي على مقررات مسندة للأستاذ
            $db = getDB();
            $stmt = $db->prepare("
                SELECT DISTINCT s.id, s.label, s.academic_year 
                FROM assignments a 
                JOIN semesters s ON a.semester_id = s.id 
                WHERE a.professor_id = ?
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $assignedSemesters = $stmt->fetchAll();
           include(__DIR__ . '/../views/professor/grades.php');
        }
    }
}