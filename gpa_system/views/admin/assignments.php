<?php 
// views/admin/assignments.php
include __DIR__ . '/../layouts/header.php'; 
?>
<h2>Course Assignments</h2>
<?= showFlash() ?>

<!-- نموذج إضافة تعيين جديد -->
<form method="post" action="index.php?page=admin.saveAssignment" class="row mb-4">
    <div class="col-md-3">
        <select name="professor_id" class="form-select" required>
            <option value="">-- Professor --</option>
            <?php foreach ($professors as $prof): ?>
                <option value="<?= $prof['id'] ?>"><?= htmlspecialchars($prof['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="course_id" class="form-select" required>
            <option value="">-- Course --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['name']) ?> (<?= $course['semester_label'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="semester_id" class="form-select" required>
            <option value="">-- Semester --</option>
            <?php foreach ($semesters as $sem): ?>
                <option value="<?= $sem['id'] ?>"><?= htmlspecialchars($sem['label']) ?> - <?= htmlspecialchars($sem['academic_year']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary">Assign</button>
    </div>
</form>

<hr>

<h4>Current Assignments</h4>
<table class="table table-bordered">
    <thead><tr><th>Professor</th><th>Course</th><th>Semester</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($assignmentsList as $ass): ?>
    <tr>
        <td><?= htmlspecialchars($ass['professor_name']) ?></td>
        <td><?= htmlspecialchars($ass['course_name']) ?></td>
        <td><?= htmlspecialchars($ass['semester_label']) ?> (<?= htmlspecialchars($ass['academic_year']) ?>)</td>
        <td>
            <form method="post" action="index.php?page=admin.deleteAssignment" onsubmit="return confirm('Delete assignment?')">
                <input type="hidden" name="assignment_id" value="<?= $ass['id'] ?>">
                <button class="btn btn-sm btn-danger">Remove</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/../layouts/footer.php'; ?>