<?php
class GpaRecord {
    public static function recompute($studentId, $semesterId) {
        $rows = Grade::getAllWithCredits($studentId, $semesterId);
        $totalPoints = 0; $totalCredits = 0;
        foreach ($rows as $r) {
            $totalPoints += $r['grade'] * $r['credits'];
            $totalCredits += $r['credits'];
        }
        if ($totalCredits > 0) {
            $gpa = round($totalPoints / $totalCredits, 2);
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO gpa_records (student_id, semester_id, gpa)
                VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE gpa=?, computed_at=CURRENT_TIMESTAMP");
            $stmt->execute([$studentId, $semesterId, $gpa, $gpa]);
        }
    }

    public static function get($studentId, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT gpa FROM gpa_records WHERE student_id = ? AND semester_id = ?");
        $stmt->execute([$studentId, $semesterId]);
        $val = $stmt->fetchColumn();
        return $val !== false ? (float)$val : null;
    }
}