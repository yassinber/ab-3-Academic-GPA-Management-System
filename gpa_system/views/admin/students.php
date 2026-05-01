<?php 
// views/admin/students.php
include __DIR__ . '/../layouts/header.php'; 
?>
<h2>Manage Students</h2>
<?= showFlash() ?>

<form method="post" action="index.php?page=admin.saveStudent" class="mb-4">
    <input type="hidden" name="id" id="studentId">
    <div class="row">
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="col-md-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="col-md-2">
            <input type="password" name="password" class="form-control" placeholder="Password (leave blank to keep)">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" onclick="clearStudentForm()">Clear</button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Enroll</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($students as $student): ?>
    <tr>
        <td><?= $student['id'] ?></td>
        <td><?= htmlspecialchars($student['name']) ?></td>
        <td><?= htmlspecialchars($student['email']) ?></td>
        <td>
            <a href="index.php?page=admin.enroll&student_id=<?= $student['id'] ?>" class="btn btn-sm btn-info">Enroll</a>
        </td>
        <td>
            <button onclick="editStudent(<?= $student['id'] ?>, '<?= addslashes($student['name']) ?>', '<?= addslashes($student['email']) ?>')" class="btn btn-sm btn-warning">Edit</button>
            <form method="post" action="index.php?page=admin.deleteStudent" style="display:inline" onsubmit="return confirm('Deleting student will remove all grades and enrollments. Continue?')">
                <input type="hidden" name="id" value="<?= $student['id'] ?>">
                <button class="btn btn-sm btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
function editStudent(id, name, email) {
    document.getElementById('studentId').value = id;
    document.querySelector('input[name="name"]').value = name;
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="password"]').value = '';
}
function clearStudentForm() {
    document.getElementById('studentId').value = '';
    document.querySelector('input[name="name"]').value = '';
    document.querySelector('input[name="email"]').value = '';
    document.querySelector('input[name="password"]').value = '';
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>