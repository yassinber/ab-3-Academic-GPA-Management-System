/**
 * student.js - Student Dashboard & History (Robust & Professional)
 * Requires jQuery
 */
$(document).ready(function () {
    if ($('#studentContent').length) {
        loadCurrentGPA();
    }
    if ($('#historyContent').length) {
        loadHistory();
    }
});

function loadCurrentGPA() {
    // عرض مؤقت: جاري التحميل
    $('#studentContent').html('<div class="card p-4 text-center"><div class="spinner-border text-primary mb-2"></div><p class="text-muted mb-0">Loading your grades...</p></div>');

    $.get('api/gpa.php', { action: 'current' })
    .done(function (data) {
        var html = '';
        // التعامل مع الخطأ الوارد من السيرفر
        if (data.error) {
            html = buildMessage('warning', data.error, 'Please make sure you are enrolled in the active semester.');
        } else if (!data.courses || data.courses.length === 0) {
            html = buildMessage('info', 'No courses yet', 'No courses have been assigned to your active semester.');
        } else {
            // بناء الواجهة الكاملة
            html = buildDashboardCard(data.semester, data.courses, data.gpa);
        }
        $('#studentContent').html(html);
    })
    .fail(function (xhr, status, error) {
        console.error('AJAX error:', status, error);
        var msg = 'Cannot connect to server. Please check your connection.';
        if (xhr.status === 403) msg = 'You are not authorized. Please login again.';
        if (xhr.status === 404) msg = 'API endpoint not found. Contact admin.';
        $('#studentContent').html(buildMessage('danger', 'Connection Error', msg));
    });
}

function loadHistory() {
    $('#historyContent').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
    $.get('api/gpa.php', { action: 'history' })
    .done(function (semesters) {
        if (!semesters || semesters.length === 0) {
            $('#historyContent').html(buildMessage('info', 'No history available', 'Your academic history will appear here.'));
            return;
        }
        var html = '';
        $.each(semesters, function (i, sem) {
            html += buildHistoryCard(sem);
        });
        $('#historyContent').html(html);
    })
    .fail(function () {
        $('#historyContent').html(buildMessage('danger', 'Failed to load history', 'An error occurred.'));
    });
}

// --- Build Functions ---
function buildDashboardCard(semester, courses, gpa) {
    var html = '<div class="card shadow-sm mb-4"><div class="card-header bg-primary bg-opacity-10 fw-bold text-primary"><i class="bi bi-calendar3 me-2"></i>' +
        escapeHtml(semester.label) + ' - ' + escapeHtml(semester.academic_year) + '</div><div class="card-body">';
    html += '<div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr>' +
        '<th>Course</th><th class="text-center">Credits</th><th class="text-center">Grade</th><th class="text-center">Points</th></thead><tbody>';
    $.each(courses, function (i, c) {
        var gradeBadge = c.grade !== null ? '<span class="badge ' + getGradeBadgeClass(c.grade) + ' fs-6">' + c.grade.toFixed(1) + '</span>' : '<span class="text-muted fst-italic">Pending</span>';
        html += '<tr><td>' + escapeHtml(c.course_name) + '</td><td class="text-center">' + c.credits + '</td><td class="text-center">' + gradeBadge + '</td><td class="text-center">' + (c.grade !== null ? c.grade_points.toFixed(1) : '-') + '</td></tr>';
    });
    html += '</tbody></table></div>';
    var gpaClass = gpa !== null ? getGpaClass(gpa) : 'alert-secondary';
    var gpaText = gpa !== null ? gpa.toFixed(2) : '--';
    html += '<div class="d-flex justify-content-end mt-3"><div class="alert ' + gpaClass + ' mb-0 px-4 py-2 d-flex align-items-center rounded-3"><i class="bi bi-trophy-fill fs-3 me-3"></i><div><div class="fw-bold">GPA</div><div class="fs-3 fw-bold">' + gpaText + '</div></div></div></div>';
    html += '</div></div>';
    return html;
}

function buildHistoryCard(sem) {
    var html = '<div class="card shadow-sm mb-4"><div class="card-header d-flex justify-content-between align-items-center bg-light">' +
        '<h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>' + escapeHtml(sem.label) + ' <small class="text-muted">' + escapeHtml(sem.academic_year) + '</small></h5>';
    html += sem.gpa !== null ? '<span class="badge ' + getGpaClass(sem.gpa).replace('alert-','bg-') + ' fs-6">GPA: ' + sem.gpa.toFixed(2) + '</span>' : '<span class="badge bg-secondary">GPA N/A</span>';
    html += '</div><div class="card-body p-0"><table class="table table-sm table-striped mb-0"><thead class="table-secondary"><tr><th>Course</th><th class="text-center">Credits</th><th class="text-center">Grade</th><th class="text-center">Points</th></tr></thead><tbody>';
    $.each(sem.courses, function (j, c) {
        var gradeBadge = c.grade !== null ? '<span class="badge ' + getGradeBadgeClass(c.grade) + '">' + c.grade.toFixed(1) + '</span>' : '<span class="text-muted">N/A</span>';
        html += '<tr><td>' + escapeHtml(c.name) + '</td><td class="text-center">' + c.credits + '</td><td class="text-center">' + gradeBadge + '</td><td class="text-center">' + (c.grade !== null ? c.grade_points.toFixed(1) : '-') + '</td></tr>';
    });
    html += '</tbody></table></div></div>';
    return html;
}

// --- Helpers ---
function buildMessage(type, title, msg) {
    var icon = 'bi-info-circle';
    if (type === 'warning') icon = 'bi-exclamation-triangle';
    if (type === 'danger') icon = 'bi-x-circle';
    return '<div class="card shadow-sm text-center py-5"><div class="card-body"><i class="bi ' + icon + ' text-' + type + ' display-3"></i><h4 class="mt-3">' + escapeHtml(title) + '</h4><p class="text-muted">' + escapeHtml(msg) + '</p></div></div>';
}
function getGpaClass(gpa) { if (gpa >= 3.7) return 'alert-success'; if (gpa >= 3.0) return 'alert-info'; if (gpa >= 2.0) return 'alert-warning'; return 'alert-danger'; }
function getGradeBadgeClass(grade) { if (grade >= 3.0) return 'bg-success'; if (grade >= 2.0) return 'bg-warning text-dark'; return 'bg-danger'; }
function escapeHtml(text) { if (!text) return ''; return String(text).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;'); }