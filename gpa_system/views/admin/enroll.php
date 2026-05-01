<?php 
// views/admin/enroll.php
include __DIR__ . '/../layouts/header.php'; 
?>
<h2>Enroll Student in Semesters</h2>
<?= showFlash() ?>

<?php if (isset($student)): ?>
    <h4>Student: <?= htmlspecialchars($student['name']) ?> (<?= htmlspecialchars($student['email']) ?>)</h4>
    <form method="post" action="index.php?page=admin.saveEnrollments">
        <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
        <div class="row mt-3">
            <?php foreach ($allSemesters as $sem): ?>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="semester_ids[]" 
                               value="<?= $sem['id'] ?>"
                               id="sem<?= $sem['id'] ?>"
                               <?= in_array($sem['id'], $enrolledIds) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="sem<?= $sem['id'] ?>">
                            <?= htmlspecialchars($sem['label']) ?> - <?= htmlspecialchars($sem['academic_year']) ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save Enrollments</button>
        <a href="index.php?page=admin.students" class="btn btn-secondary mt-3">Back to Students</a>
    </form>
<?php else: ?>
    <div class="alert alert-danger">Student not found.</div>
<?php endif; ?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>