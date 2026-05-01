<?php
class Semester {
    public static function all() {
        $db = getDB();
        return $db->query("SELECT * FROM semesters ORDER BY academic_year DESC, id DESC")->fetchAll();
    }

    public static function create($label, $year) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO semesters (label, academic_year) VALUES (?, ?)");
        $stmt->execute([$label, $year]);
    }

    public static function update($id, $label, $year) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE semesters SET label = ?, academic_year = ? WHERE id = ?");
        $stmt->execute([$label, $year, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM semesters WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function setAllInactive() {
        getDB()->exec("UPDATE semesters SET is_active = 0");
    }

    public static function setActive($id) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE semesters SET is_active = 1 WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function getActive() {
        return getDB()->query("SELECT * FROM semesters WHERE is_active = 1 LIMIT 1")->fetch();
    }

    public static function countBySemester($semesterId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM courses WHERE semester_id = ?");
        $stmt->execute([$semesterId]);
        return $stmt->fetchColumn();
    }
}