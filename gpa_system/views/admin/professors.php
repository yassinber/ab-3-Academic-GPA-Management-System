<?php 
// views/admin/professors.php
include __DIR__ . '/../layouts/header.php'; 
?>
<h2>Manage Professors</h2>
<?= showFlash() ?>

<!-- نموذج إضافة/تعديل أستاذ -->
<form method="post" action="index.php?page=admin.saveProfessor" class="mb-4">
    <input type="hidden" name="id" id="profId">
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
            <button type="button" class="btn btn-secondary" onclick="clearProfForm()">Clear</button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($professors as $prof): ?>
    <tr>
        <td><?= $prof['id'] ?></td>
        <td><?= htmlspecialchars($prof['name']) ?></td>
        <td><?= htmlspecialchars($prof['email']) ?></td>
        <td>
            <button onclick="editProf(<?= $prof['id'] ?>, '<?= addslashes($prof['name']) ?>', '<?= addslashes($prof['email']) ?>')" class="btn btn-sm btn-warning">Edit</button>
            <form method="post" action="index.php?page=admin.deleteProfessor" style="display:inline" onsubmit="return confirm('Are you sure?')">
                <input type="hidden" name="id" value="<?= $prof['id'] ?>">
                <button class="btn btn-sm btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
function editProf(id, name, email) {
    document.getElementById('profId').value = id;
    document.querySelector('input[name="name"]').value = name;
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="password"]').value = '';
}
function clearProfForm() {
    document.getElementById('profId').value = '';
    document.querySelector('input[name="name"]').value = '';
    document.querySelector('input[name="email"]').value = '';
    document.querySelector('input[name="password"]').value = '';
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>