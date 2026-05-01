<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - GPA System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="?page=admin.dashboard">
            <i class="bi bi-mortarboard-fill me-2"></i>GPA System
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Manage
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?page=admin.semesters"><i class="bi bi-calendar3 me-2"></i>Semesters</a></li>
                        <li><a class="dropdown-item" href="?page=admin.courses"><i class="bi bi-book me-2"></i>Courses</a></li>
                        <li><a class="dropdown-item" href="?page=admin.professors"><i class="bi bi-person-badge me-2"></i>Professors</a></li>
                        <li><a class="dropdown-item" href="?page=admin.students"><i class="bi bi-people me-2"></i>Students</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=admin.assignments"><i class="bi bi-link-45deg"></i> Assignments</a>
                </li>
            </ul>
            <div class="navbar-text">
                <span class="me-3 text-light">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['name']) ?>
                    <span class="badge bg-danger ms-1">Admin</span>
                </span>
                <a href="?page=logout" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<main class="container flex-grow-1">

    <?php
    $flash = getFlash();
if ($flash):
    $cls = $flash['type'] === 'success' ? 'success' : 'danger';
?>
    <div class="alert alert-<?= $cls ?> alert-dismissible fade show">
        <?= htmlspecialchars($flash['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-book me-2"></i>Manage Courses</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
            <i class="bi bi-plus-circle me-1"></i> Add Course
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Semester</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($courses)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No courses yet. Add your first course!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $i => $c): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                            <td><span class="badge bg-info"><?= $c['credits'] ?> credits</span></td>
                            <td><?= htmlspecialchars($c['label'] ?? '') ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1"
                                    onclick="editCourse(<?= $c['id'] ?>, '<?= htmlspecialchars($c['name']) ?>', <?= $c['credits'] ?>, <?= $c['semester_id'] ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="?page=admin.deleteCourse" style="display:inline"
                                      onsubmit="return confirm('Delete this course?')">
                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<footer class="text-center text-muted py-3 mt-5 border-top">
    <small>&copy; <?= date('Y') ?> GPA Management System</small>
</footer>

<!-- Modal إضافة مادة -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="?page=admin.saveCourse">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Course</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Course Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Mathematics" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Credits</label>
                        <input type="number" name="credits" class="form-control" min="1" max="6" placeholder="e.g. 4" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Semester</label>
                        <select name="semester_id" class="form-select" required>
                            <option value="">-- Select Semester --</option>
                            <?php foreach ($semesters as $s): ?>
                                <option value="<?= $s['id'] ?>">
                                    <?= htmlspecialchars($s['label'] . ' - ' . $s['academic_year']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal تعديل مادة -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="?page=admin.saveCourse">
                <input type="hidden" name="id" id="edit_course_id">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Course Name</label>
                        <input type="text" name="name" id="edit_course_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Credits</label>
                        <input type="number" name="credits" id="edit_course_credits" class="form-control" min="1" max="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Semester</label>
                        <select name="semester_id" id="edit_course_semester" class="form-select" required>
                            <?php foreach ($semesters as $s): ?>
                                <option value="<?= $s['id'] ?>">
                                    <?= htmlspecialchars($s['label'] . ' - ' . $s['academic_year']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCourse(id, name, credits, semesterId) {
    document.getElementById('edit_course_id').value = id;
    document.getElementById('edit_course_name').value = name;
    document.getElementById('edit_course_credits').value = credits;
    document.getElementById('edit_course_semester').value = semesterId;
    new bootstrap.Modal(document.getElementById('editCourseModal')).show();
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>