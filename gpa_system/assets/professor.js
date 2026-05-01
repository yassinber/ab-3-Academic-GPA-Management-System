/**
 * professor.js - AJAX grade entry logic
 * Requires jQuery
 */

$(document).ready(function () {

    // Semester change: load courses assigned to this professor in the selected semester
    $('#semesterSelect').change(function () {
        var semId = $(this).val();
        if (!semId) {
            $('#courseSelect').html('<option value="">-- Select course --</option>').prop('disabled', true);
            $('#gradeTable').hide();
            $('#saveBtn').hide();
            $('#feedback').empty();
            return;
        }

        $.get('api/grades.php', {
            action: 'courses',
            semester_id: semId
        }, function (data) {
            var opts = '<option value="">-- Select course --</option>';
            $.each(data, function (i, course) {
                opts += '<option value="' + course.id + '">' + course.name + '</option>';
            });
            $('#courseSelect').html(opts).prop('disabled', false);
            $('#gradeTable').hide();
            $('#saveBtn').hide();
            $('#feedback').empty();
        }, 'json').fail(function () {
            alert('Error loading courses');
        });
    });

    // Course change: load enrolled students with their current grades
    $('#courseSelect').change(function () {
        var semId = $('#semesterSelect').val();
        var courseId = $(this).val();
        if (!courseId) {
            $('#gradeTable').hide();
            $('#saveBtn').hide();
            return;
        }

        $.get('api/grades.php', {
            action: 'students',
            semester_id: semId,
            course_id: courseId
        }, function (students) {
            var tbody = '';
            $.each(students, function (i, s) {
                var gradeVal = (s.grade !== null && s.grade !== undefined) ? s.grade : '';
                tbody += '<tr>' +
                    '<td>' + escapeHtml(s.name) + '</td>' +
                    '<td>' + s.id + '</td>' +
                    '<td>' +
                    '<select class="form-control grade-input" data-student="' + s.id + '">' +
                    buildOptions(gradeVal) +
                    '</select>' +
                    '</td>' +
                    '</tr>';
            });
            $('#gradeTable tbody').html(tbody);
            $('#gradeTable').show();
            $('#saveBtn').show();
            $('#feedback').empty();
        }, 'json').fail(function () {
            alert('Error loading students');
        });
    });

    // Save grades button
    $('#saveBtn').click(function () {
        var semId = $('#semesterSelect').val();
        var courseId = $('#courseSelect').val();
        var grades = [];

        $('.grade-input').each(function () {
            var studentId = $(this).data('student');
            var gradeVal = $(this).val();
            if (gradeVal !== '') {
                grades.push({
                    student_id: studentId,
                    grade: gradeVal
                });
            }
        });

        if (grades.length === 0) {
            showAlert('warning', 'No grades selected.');
            return;
        }

        $.post('api/grades.php', {
            action: 'save',
            semester_id: semId,
            course_id: courseId,
            grades: grades
        }, function (response) {
            if (response.success) {
                showAlert('success', response.saved + ' grade(s) saved successfully.');
            } else {
                showAlert('danger', response.error || 'Unknown error');
            }
        }, 'json').fail(function () {
            showAlert('danger', 'Server error while saving grades.');
        });
    });

    // Utility: build <option> list for grade dropdown
    function buildOptions(selected) {
        var grades = [
            { value: '', label: '-- Grade --' },
            { value: '4.0', label: 'A (4.0)' },
            { value: '3.0', label: 'B (3.0)' },
            { value: '2.0', label: 'C (2.0)' },
            { value: '1.0', label: 'D (1.0)' },
            { value: '0.0', label: 'F (0.0)' }
        ];
        var html = '';
        $.each(grades, function (i, g) {
            var sel = (String(g.value) === String(selected)) ? ' selected' : '';
            html += '<option value="' + g.value + '"' + sel + '>' + g.label + '</option>';
        });
        return html;
    }

    // Utility: show Bootstrap alert in #feedback
    function showAlert(type, message) {
        var html = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>';
        $('#feedback').html(html);
    }

    // Basic HTML escaping to prevent XSS
    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
});