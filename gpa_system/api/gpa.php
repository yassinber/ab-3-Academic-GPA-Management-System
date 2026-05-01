<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    http_response_code(403);
    echo json_encode(['error' => 'Please login as student']);
    exit;
}

// اتصال بقاعدة البيانات (عدّل الإعدادات إن لزم)
$pdo = new PDO('mysql:host=localhost;dbname=gpa_system;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$studentId = (int) $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

if ($action === 'current') {
    // الفصل النشط
    $stmt = $pdo->query("SELECT * FROM semesters WHERE is_active = 1 LIMIT 1");
    $semester = $stmt->fetch();

    if (!$semester) {
        echo json_encode(['error' => 'No active semester']);
        exit;
    }

    // هل الطالب مسجل؟
    $stmt = $pdo->prepare("SELECT 1 FROM enrollments WHERE student_id=? AND semester_id=?");
    $stmt->execute([$studentId, $semester['id']]);
    if (!$stmt->fetch()) {
        echo json_encode(['error' => 'Not enrolled in active semester']);
        exit;
    }

    // جلب المقررات مع الدرجات
    $stmt = $pdo->prepare("
        SELECT c.name AS course_name, c.credits, g.grade
        FROM courses c
        LEFT JOIN grades g ON g.course_id=c.id AND g.student_id=? AND g.semester_id=?
        WHERE c.semester_id=?
    ");
    $stmt->execute([$studentId, $semester['id'], $semester['id']]);
    $courses = $stmt->fetchAll();

    $enriched = [];
    foreach ($courses as $c) {
        $grade = $c['grade'] !== null ? (float)$c['grade'] : null;
        $enriched[] = [
            'course_name' => $c['course_name'],
            'credits' => (int)$c['credits'],
            'grade' => $grade,
            'grade_points' => $grade !== null ? round($grade * $c['credits'], 1) : 0
        ];
    }

    // GPA
    $stmt = $pdo->prepare("SELECT gpa FROM gpa_records WHERE student_id=? AND semester_id=?");
    $stmt->execute([$studentId, $semester['id']]);
    $gpaRow = $stmt->fetch();
    $gpa = $gpaRow ? (float)$gpaRow['gpa'] : null;

    echo json_encode([
        'semester' => $semester,
        'courses' => $enriched,
        'gpa' => $gpa
    ]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid action']);