<?php
session_start();
header('Content-Type: application/json');

// التأكد من تسجيل الدخول كأستاذ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'professor') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$profId = (int) $_SESSION['user_id'];
$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// اتصال مستقل بقاعدة البيانات (عدّل كلمة المرور إن لزم)
$pdo = new PDO('mysql:host=localhost;dbname=gpa_system;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// --- action: courses ---
if ($action === 'courses' && $method === 'GET') {
    $semId = isset($_GET['semester_id']) ? (int)$_GET['semester_id'] : 0;
    if ($semId <= 0) {
        echo json_encode([]);
        exit;
    }
    $stmt = $pdo->prepare("SELECT c.id, c.name FROM courses c JOIN assignments a ON c.id = a.course_id WHERE a.professor_id = ? AND a.semester_id = ?");
    $stmt->execute([$profId, $semId]);
    echo json_encode($stmt->fetchAll());
    exit;
}

// --- action: students ---
if ($action === 'students' && $method === 'GET') {
    $semId = isset($_GET['semester_id']) ? (int)$_GET['semester_id'] : 0;
    $courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
    if ($semId <= 0 || $courseId <= 0) {
        echo json_encode([]);
        exit;
    }
    // تحقق من إسناد الأستاذ
    $stmt = $pdo->prepare("SELECT id FROM assignments WHERE professor_id=? AND course_id=? AND semester_id=?");
    $stmt->execute([$profId, $courseId, $semId]);
    if (!$stmt->fetch()) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $pdo->prepare("SELECT u.id, u.name, g.grade FROM enrollments e JOIN users u ON e.student_id = u.id LEFT JOIN grades g ON g.student_id=u.id AND g.course_id=? AND g.semester_id=? WHERE e.semester_id=? ORDER BY u.name");
    $stmt->execute([$courseId, $semId, $semId]);
    $students = $stmt->fetchAll();
    $result = array_map(function($s) {
        return [
            'id' => (int)$s['id'],
            'name' => $s['name'],
            'grade' => $s['grade'] !== null ? (float)$s['grade'] : null
        ];
    }, $students);
    echo json_encode($result);
    exit;
}

// --- action: save (POST) ---
if ($action === 'save' && $method === 'POST') {
    $semId = isset($_POST['semester_id']) ? (int)$_POST['semester_id'] : 0;
    $courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
    if ($semId <= 0 || $courseId <= 0) {
        echo json_encode(['error' => 'Missing parameters']);
        exit;
    }
    // تحقق من الإسناد
    $stmt = $pdo->prepare("SELECT id FROM assignments WHERE professor_id=? AND course_id=? AND semester_id=?");
    $stmt->execute([$profId, $courseId, $semId]);
    if (!$stmt->fetch()) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $grades = $_POST['grades'] ?? [];
    $valid = [0.0, 1.0, 2.0, 3.0, 4.0];
    $saved = 0;
    foreach ($grades as $g) {
        $sid = (int)($g['student_id'] ?? 0);
        $grade = (float)($g['grade'] ?? -1);
        if ($sid <= 0 || !in_array($grade, $valid)) continue;
        $stmt = $pdo->prepare("INSERT INTO grades (student_id, course_id, semester_id, professor_id, grade) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE grade=?, professor_id=?, entered_at=CURRENT_TIMESTAMP");
        $stmt->execute([$sid, $courseId, $semId, $profId, $grade, $grade, $profId]);
        $saved++;
        // إعادة حساب GPA
        $stmt2 = $pdo->prepare("SELECT g.grade, c.credits FROM grades g JOIN courses c ON g.course_id=c.id WHERE g.student_id=? AND g.semester_id=?");
        $stmt2->execute([$sid, $semId]);
        $totalPoints = 0; $totalCredits = 0;
        foreach ($stmt2->fetchAll() as $r) {
            $totalPoints += $r['grade'] * $r['credits'];
            $totalCredits += $r['credits'];
        }
        if ($totalCredits > 0) {
            $gpa = round($totalPoints / $totalCredits, 2);
            $stmt3 = $pdo->prepare("INSERT INTO gpa_records (student_id, semester_id, gpa) VALUES (?,?,?) ON DUPLICATE KEY UPDATE gpa=?, computed_at=CURRENT_TIMESTAMP");
            $stmt3->execute([$sid, $semId, $gpa, $gpa]);
        }
    }
    echo json_encode(['success' => true, 'saved' => $saved]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid action']);