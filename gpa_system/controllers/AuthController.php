<?php
require_once 'models/User.php';

class AuthController {
    public function handle($action) {
        if ($action === 'login') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = sanitize($_POST['email']);
                $password = $_POST['password'];
                $user = User::findByEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['last_activity'] = time();
                    $dashboards = [
                        'admin' => 'admin.dashboard',
                        'professor' => 'professor.grades',
                        'student' => 'student.dashboard'
                    ];
                    header('Location: index.php?page=' . $dashboards[$user['role']]);
                    exit;
                } else {
                    flash('danger', 'Invalid email or password');
                    header('Location: index.php?page=login');
                    exit;
                }
            }
            include 'views/login.php';
        } elseif ($action === 'logout') {
            session_destroy();
            header('Location: index.php?page=login');
            exit;
        }
    }
}