<?php
// views/professor/grades.php

// إذا تم استدعاء الصفحة عبر AJAX، عالج الطلب وأرجع JSON وتوقف
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    session_start();
    header('Content-Type: application/json');

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'professor') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $profId = (int) $_SESSION['user_id'];
    $action = $_GET['action'] ?? '';
    $method = $_SERVER['REQUEST_METHOD'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=gpa_system;charset=utf8mb4', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // --- action: courses ---
        if ($action === 'courses' && $method === 'GET') {
            $semId = isset($_GET['semester_id']) ? (int)$_GET['semester_id'] : 0;
            if ($semId <= 0) { echo json_encode([]); exit; }
            $stmt = $pdo->prepare("SELECT c.id, c.name FROM courses c JOIN assignments a ON c.id = a.course_id WHERE a.professor_id = ? AND a.semester_id = ?");
            $stmt->execute([$profId, $semId]);
            echo json_encode($stmt->fetchAll());
            exit;
        }

        // --- action: students ---
        if ($action === 'students' && $method === 'GET') {
            $semId = isset($_GET['semester_id']) ? (int)$_GET['semester_id'] : 0;
            $courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
            if ($semId <= 0 || $courseId <= 0) { echo json_encode([]); exit; }
            // تحقق من الإسناد
            $stmt = $pdo->prepare("SELECT id FROM assignments WHERE professor_id=? AND course_id=? AND semester_id=?");
            $stmt->execute([$profId, $courseId, $semId]);
            if (!$stmt->fetch()) {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }
            // جلب الطلاب المسجلين مع علاماتهم
            $stmt = $pdo->prepare("SELECT u.id, u.name, g.grade FROM enrollments e JOIN users u ON e.student_id = u.id LEFT JOIN grades g ON g.student_id=u.id AND g.course_id=? AND g.semester_id=? WHERE e.semester_id=? ORDER BY u.name");
            $stmt->execute([$courseId, $semId, $semId]);
            $students = $stmt->fetchAll();
            $result = array_map(function($s) {
                return ['id' => (int)$s['id'], 'name' => $s['name'], 'grade' => $s['grade'] !== null ? (float)$s['grade'] : null];
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
                // GPA
                $stmt2 = $pdo->prepare("SELECT g.grade, c.credits FROM grades g JOIN courses c ON g.course_id=c.id WHERE g.student_id=? AND g.semester_id=?");
                $stmt2->execute([$sid, $semId]);
                $tp = 0; $tc = 0;
                foreach ($stmt2->fetchAll() as $r) { $tp += $r['grade']*$r['credits']; $tc += $r['credits']; }
                if ($tc > 0) {
                    $gpa = round($tp / $tc, 2);
                    $stmt3 = $pdo->prepare("INSERT INTO gpa_records (student_id, semester_id, gpa) VALUES (?,?,?) ON DUPLICATE KEY UPDATE gpa=?, computed_at=CURRENT_TIMESTAMP");
                    $stmt3->execute([$sid, $semId, $gpa, $gpa]);
                }
            }
            echo json_encode(['success' => true, 'saved' => $saved]);
            exit;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
    exit;
}

// ----------------------------------------------
// عرض الواجهة (ليس طلب AJAX)
// ----------------------------------------------
include __DIR__ . '/../layouts/header.php';

// جلب الفصول التي يدرسها الأستاذ
try {
    $pdo = new PDO('mysql:host=localhost;dbname=gpa_system;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT DISTINCT s.id, s.label, s.academic_year FROM assignments a JOIN semesters s ON a.semester_id = s.id WHERE a.professor_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $assignedSemesters = $stmt->fetchAll();
} catch (Exception $e) {
    $assignedSemesters = [];
}
?>

<h2 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2 text-success"></i>إضافة / تعديل العلامات</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label fw-bold">الفصل الدراسي</label>
                <select id="semesterSelect" class="form-select">
                    <option value="">-- اختر الفصل --</option>
                    <?php foreach ($assignedSemesters as $sem): ?>
                        <option value="<?= $sem['id'] ?>"><?= htmlspecialchars($sem['label']) ?> - <?= htmlspecialchars($sem['academic_year']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold">المقياس</label>
                <select id="courseSelect" class="form-select" disabled>
                    <option value="">-- اختر المقياس --</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div id="feedback" class="mb-3"></div>

<div class="card shadow-sm" id="gradeCard" style="display: none;">
    <div class="card-header bg-dark text-white fw-bold"><i class="bi bi-table me-2"></i>قائمة الطلاب</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light"><tr><th>#</th><th>الطالب</th><th>الرقم</th><th>العلامة</th></tr></thead>
                <tbody id="studentTableBody"></tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-end">
        <button id="saveBtn" class="btn btn-success"><i class="bi bi-save"></i> حفظ العلامات</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    $('#semesterSelect').change(function () {
        var semId = $(this).val();
        $('#courseSelect').prop('disabled', true).html('<option value="">-- اختر المقياس --</option>');
        $('#gradeCard').hide(); $('#feedback').empty();
        if (!semId) return;
        $.get('index.php?page=professor.grades&ajax=1&action=courses&semester_id=' + semId, function (data) {
            var opts = '<option value="">-- اختر المقياس --</option>';
            $.each(data, function (i, c) { opts += '<option value="' + c.id + '">' + c.name + '</option>'; });
            $('#courseSelect').html(opts).prop('disabled', false);
        }, 'json');
    });

    $('#courseSelect').change(function () {
        var semId = $('#semesterSelect').val();
        var courseId = $(this).val();
        if (!courseId) { $('#gradeCard').hide(); return; }
        $.get('index.php?page=professor.grades&ajax=1&action=students&semester_id=' + semId + '&course_id=' + courseId, function (students) {
            var rows = '';
            $.each(students, function (i, s) {
                var gradeVal = (s.grade != null) ? s.grade : '';
                rows += '<tr><td>' + (i+1) + '</td><td>' + esc(s.name) + '</td><td>' + s.id + '</td><td>' +
                    '<select class="form-select grade-input" data-student="' + s.id + '">' + buildOptions(gradeVal) + '</select></td></tr>';
            });
            $('#studentTableBody').html(rows);
            $('#gradeCard').show(); $('#feedback').empty();
        }, 'json');
    });

    $('#saveBtn').click(function () {
        var semId = $('#semesterSelect').val(), courseId = $('#courseSelect').val();
        var grades = [];
        $('.grade-input').each(function () {
            var v = $(this).val();
            if (v !== '') grades.push({ student_id: $(this).data('student'), grade: v });
        });
        if (grades.length === 0) { showAlert('warning', 'لم تختر أي علامة.'); return; }
        $.post('index.php?page=professor.grades&ajax=1&action=save', {
            semester_id: semId, course_id: courseId, grades: grades
        }, function (resp) {
            resp.success ? showAlert('success', 'تم حفظ ' + resp.saved + ' علامة بنجاح.') : showAlert('danger', resp.error);
        }, 'json').fail(function () { showAlert('danger', 'فشل الاتصال.'); });
    });

    function buildOptions(selected) {
        var vals = [{v:'',l:'-- العلامة --'},{v:'4.0',l:'A (4.0)'},{v:'3.0',l:'B (3.0)'},{v:'2.0',l:'C (2.0)'},{v:'1.0',l:'D (1.0)'},{v:'0.0',l:'F (0.0)'}];
        var h = '';
        $.each(vals, function (i, o) { var s = String(o.v) === String(selected) ? ' selected' : ''; h += '<option value="'+o.v+'"'+s+'>'+o.l+'</option>'; });
        return h;
    }
    function showAlert(type, msg) { $('#feedback').html('<div class="alert alert-'+type+' alert-dismissible fade show">'+msg+'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'); }
    function esc(t) { return String(t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>