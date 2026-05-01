<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', '/gpa_system');
define('DB_HOST', 'localhost');
define('DB_NAME', 'gpa_system');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $host = 'localhost';
        $db   = 'gpa_system';        // تأكد من اسم القاعدة
        $user = 'root';              // في XAMPP الافتراضي root
        $pass = '';                  // بدون كلمة مرور
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // عرض الخطأ بشكل واضح حتى تصححه
            die("❌ فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
    }
    return $pdo;
}

function requireRole($expectedRole) {
    if (!isset($_SESSION['role']) || (time() - ($_SESSION['last_activity'] ?? 0)) > 1800) {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
    if ($_SESSION['role'] !== $expectedRole) {
        http_response_code(403);
        die("Access Denied");
    }
    $_SESSION['last_activity'] = time();
}

function flash($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

// دالة تعيد HTML جاهز للعرض
function showFlash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return '<div class="alert alert-'.$f['type'].' alert-dismissible fade show" role="alert">
                  '.$f['msg'].'
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
    }
    return '';
}

// دالة تعيد المصفوفة مباشرة
function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($page) {
    header('Location: index.php?page=' . $page);
    exit;
}