<?php
class Course {
    public static function getBySemester($semesterId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM courses WHERE semester_id = ?");
        $stmt->execute([$semesterId]);
        return $stmt->fetchAll();
    }

    public static function create($name, $credits, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO courses (name, credits, semester_id) VALUES (?, ?, ?)");
        $stmt->execute([$name, $credits, $semesterId]);
    }

    public static function update($id, $name, $credits, $semesterId) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE courses SET name = ?, credits = ?, semester_id = ? WHERE id = ?");
        $stmt->execute([$name, $credits, $semesterId, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function countBySemester($semesterId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM courses WHERE semester_id = ?");
        $stmt->execute([$semesterId]);
        return $stmt->fetchColumn();
    }
}