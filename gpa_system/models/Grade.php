<?php
class Grade {
    public static function get($studentId, $courseId, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT grade FROM grades WHERE student_id=? AND course_id=? AND semester_id=?");
        $stmt->execute([$studentId, $courseId, $semesterId]);
        $val = $stmt->fetchColumn();
        return $val !== false ? (float)$val : null;
    }

    public static function upsert($studentId, $courseId, $semesterId, $professorId, $grade) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO grades (student_id, course_id, semester_id, professor_id, grade)
            VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE grade=?, professor_id=?, entered_at=CURRENT_TIMESTAMP");
        $stmt->execute([$studentId, $courseId, $semesterId, $professorId, $grade, $grade, $professorId]);
    }

    public static function countByCourse($courseId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM grades WHERE course_id = ?");
        $stmt->execute([$courseId]);
        return $stmt->fetchColumn();
    }

    public static function getAllWithCredits($studentId, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT g.grade, c.credits 
            FROM grades g 
            JOIN courses c ON g.course_id = c.id 
            WHERE g.student_id = ? AND g.semester_id = ?
        ");
        $stmt->execute([$studentId, $semesterId]);
        return $stmt->fetchAll();
    }
}