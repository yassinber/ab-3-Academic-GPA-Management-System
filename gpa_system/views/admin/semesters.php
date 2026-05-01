<?php include __DIR__ . '/../layouts/header.php'; ?>
<h2>Manage Semesters</h2>
<?= showFlash() ?>
<form method="post" action="index.php?page=admin.saveSemester" class="mb-3">
    <input type="hidden" name="id" id="semId">
    <div class="row">
        <div class="col-md-3">
            <input type="text" name="label" class="form-control" placeholder="S1" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="academic_year" class="form-control" placeholder="2024/2025" required>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" onclick="clearForm()">Clear</button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Label</th><th>Year</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($semesters as $sem): ?>
    <tr>
        <td><?= $sem['id'] ?></td>
        <td><?= $sem['label'] ?></td>
        <td><?= $sem['academic_year'] ?></td>
        <td><?= $sem['is_active'] ? 'Yes' : 'No' ?></td>
        <td>
            <a href="#" onclick="editSemester(<?= $sem['id'] ?>, '<?= $sem['label'] ?>', '<?= $sem['academic_year'] ?>')" class="btn btn-sm btn-warning">Edit</a>
            <form method="post" action="index.php?page=admin.toggleSemester" style="display:inline">
                <input type="hidden" name="id" value="<?= $sem['id'] ?>">
                <button class="btn btn-sm btn-info">Set Active</button>
            </form>
            <form method="post" action="index.php?page=admin.deleteSemester" style="display:inline">
                <input type="hidden" name="id" value="<?= $sem['id'] ?>">
                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<script>
function editSemester(id, label, year) {
    document.getElementById('semId').value = id;
    document.querySelector('input[name="label"]').value = label;
    document.querySelector('input[name="academic_year"]').value = year;
}
function clearForm() {
    document.getElementById('semId').value = '';
    document.querySelector('input[name="label"]').value = '';
    document.querySelector('input[name="academic_year"]').value = '';
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>