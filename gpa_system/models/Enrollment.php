<?php
class Enrollment {
    public static function create($studentId, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO enrollments (student_id, semester_id) VALUES (?, ?)");
        $stmt->execute([$studentId, $semesterId]);
    }

    public static function delete($studentId, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM enrollments WHERE student_id = ? AND semester_id = ?");
        $stmt->execute([$studentId, $semesterId]);
    }

    public static function getSemesterIds($studentId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT semester_id FROM enrollments WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getStudentsBySemester($semesterId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT u.id, u.name 
            FROM enrollments e 
            JOIN users u ON e.student_id = u.id 
            WHERE e.semester_id = ?
            ORDER BY u.name
        ");
        $stmt->execute([$semesterId]);
        return $stmt->fetchAll();
    }

    public static function exists($studentId, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM enrollments WHERE student_id = ? AND semester_id = ?");
        $stmt->execute([$studentId, $semesterId]);
        return (bool) $stmt->fetchColumn();
    }
}