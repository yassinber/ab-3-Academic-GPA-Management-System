<?php
class Assignment {
    public static function create($professorId, $courseId, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO assignments (professor_id, course_id, semester_id) VALUES (?, ?, ?)");
        $stmt->execute([$professorId, $courseId, $semesterId]);
    }

    public static function exists($professorId, $courseId, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM assignments WHERE professor_id = ? AND course_id = ? AND semester_id = ?");
        $stmt->execute([$professorId, $courseId, $semesterId]);
        return (bool) $stmt->fetchColumn();
    }

    public static function courseAlreadyAssigned($courseId, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM assignments WHERE course_id = ? AND semester_id = ?");
        $stmt->execute([$courseId, $semesterId]);
        return (bool) $stmt->fetchColumn();
    }

    public static function getProfessorCourses($professorId, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT c.id, c.name 
            FROM assignments a 
            JOIN courses c ON a.course_id = c.id 
            WHERE a.professor_id = ? AND a.semester_id = ?
        ");
        $stmt->execute([$professorId, $semesterId]);
        return $stmt->fetchAll();
    }
}