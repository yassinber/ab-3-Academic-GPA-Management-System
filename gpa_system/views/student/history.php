<?php 
// views/student/history.php
include __DIR__ . '/../layouts/header.php'; 
?>

<h2>My GPA History</h2>
<div id="historyContent">
    <div class="text-center mt-5">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p>Loading history...</p>
    </div>
</div>

<!-- رابط لتصدير CSV -->
<a href="api/gpa.php?action=export" class="btn btn-outline-primary mt-3 mb-3">
    <i class="bi bi-download"></i> Export CSV
</a>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/student.js"></script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>