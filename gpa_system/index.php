<?php
// تفعيل عرض الأخطاء مؤقتاً (يمكن إزالته بعد التأكد من عمل النظام)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// تضمين الإعدادات العامة (يبدأ الجلسة ويُعرِّف دوال الحماية والاتصال)
require_once __DIR__ . '/config.php';

// قراءة الصفحة المطلوبة
$page = $_GET['page'] ?? 'login';

// التوجيه بناءً على اسم الصفحة
switch (true) {
    // ---------- المصادقة ----------
    case $page === 'login':
    case $page === 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->handle($page);
        break;

    // ---------- لوحة الأدمن ----------
    case str_starts_with($page, 'admin.'):
        requireRole('admin');
        require_once __DIR__ . '/controllers/AdminController.php';
        (new AdminController())->handle($page);
        break;

    // ---------- لوحة الأستاذ ----------
    case str_starts_with($page, 'professor.'):
        requireRole('professor');
        require_once __DIR__ . '/controllers/ProfessorController.php';
        (new ProfessorController())->handle($page);
        break;

    // ---------- لوحة الطالب ----------
    case str_starts_with($page, 'student.'):
        requireRole('student');
        require_once __DIR__ . '/controllers/StudentController.php';
        (new StudentController())->handle($page);
        break;

    // ---------- غير معروف ----------
    default:
        header('Location: index.php?page=login');
        exit;
}