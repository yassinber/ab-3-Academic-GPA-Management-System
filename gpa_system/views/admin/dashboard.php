<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2 class="mb-4"><i class="bi bi-grid-fill me-2"></i>Admin Dashboard</h2>

<div class="row g-3">
    <div class="col-md-6 col-lg-3">
        <div class="card bg-primary bg-gradient text-white h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Students</h5>
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
                <h1 class="display-4 mt-auto"><?= $totalStudents ?? 0 ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-success bg-gradient text-white h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Professors</h5>
                    <i class="bi bi-person-badge-fill fs-3"></i>
                </div>
                <h1 class="display-4 mt-auto"><?= $totalProfessors ?? 0 ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-warning bg-gradient text-dark h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Semesters</h5>
                    <i class="bi bi-calendar-check-fill fs-3"></i>
                </div>
                <h1 class="display-4 mt-auto"><?= $totalSemesters ?? 0 ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-info bg-gradient text-white h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Courses</h5>
                    <i class="bi bi-book-fill fs-3"></i>
                </div>
                <h1 class="display-4 mt-auto"><?= $totalCourses ?? 0 ?></h1>
            </div>
        </div>
    </div>
</div>

<!-- يمكن إضافة رسم بياني هنا لاحقًا -->

<?php include __DIR__ . '/../layouts/footer.php'; ?>