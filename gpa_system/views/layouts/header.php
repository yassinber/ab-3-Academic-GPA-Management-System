<?php
$currentRole = $_SESSION['role'] ?? 'guest';
$userName   = $_SESSION['name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPA Management System</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- خطوط Google (اختياري) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-mortarboard-fill me-2"></i>GPA System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($currentRole === 'admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-gear"></i> Manage
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=admin.semesters"><i class="bi bi-calendar3"></i> Semesters</a></li>
                            <li><a class="dropdown-item" href="?page=admin.courses"><i class="bi bi-book"></i> Courses</a></li>
                            <li><a class="dropdown-item" href="?page=admin.professors"><i class="bi bi-person-badge"></i> Professors</a></li>
                            <li><a class="dropdown-item" href="?page=admin.students"><i class="bi bi-people"></i> Students</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-tools"></i> Operations
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=admin.assignments"><i class="bi bi-link-45deg"></i> Assignments</a></li>
                        </ul>
                    </li>
                <?php elseif ($currentRole === 'professor'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=professor.grades"><i class="bi bi-pencil-square"></i> Grade Entry</a>
                    </li>
                <?php elseif ($currentRole === 'student'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=student.dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=student.history"><i class="bi bi-clock-history"></i> GPA History</a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="navbar-text">
                <span class="me-3 text-light">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($userName) ?>
                    <span class="badge bg-secondary ms-1"><?= ucfirst($currentRole) ?></span>
                </span>
                <a href="?page=logout" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
    </div>
</nav>

<main class="container flex-grow-1">
    <?= showFlash() ?>