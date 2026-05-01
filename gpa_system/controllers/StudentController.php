<?php
/**
 * StudentController - خاص بطلبات دور الطالب
 */
class StudentController
{
    public function handle(string $page): void
    {
        $action = str_replace('student.', '', $page);

        switch ($action) {
            case 'dashboard':
                $this->dashboard();
                break;
            case 'history':
                $this->history();
                break;
            default:
                // أي رابط غير معروف يذهب إلى dashboard
                $this->dashboard();
                break;
        }
    }

    private function dashboard(): void
    {
        $view = __DIR__ . '/../views/student/dashboard.php';
        if (file_exists($view)) {
            include $view;
        } else {
            echo '<div class="alert alert-danger">Dashboard view not found.</div>';
        }
    }

    private function history(): void
    {
        $view = __DIR__ . '/../views/student/history.php';
        if (file_exists($view)) {
            include $view;
        } else {
            echo '<div class="alert alert-danger">History view not found.</div>';
        }
    }
}