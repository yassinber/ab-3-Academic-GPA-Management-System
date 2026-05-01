<?php
require_once __DIR__ . '/../models/Semester.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/Grade.php';

class AdminController {

    public function handle($page) {
        switch ($page) {

            // ================= Dashboard =================
            case 'admin.dashboard':
                include __DIR__ . '/../views/admin/dashboard.php';
                break;

            // ================= Students =================
            case 'admin.students':
                $students = User::getAllByRole('student');
                include __DIR__ . '/../views/admin/students.php';
                break;

            case 'admin.saveStudent':
                $this->saveStudent();
                break;

            case 'admin.deleteStudent':
                $this->deleteStudent();
                break;

            // ================= Professors =================
            case 'admin.professors':
                $professors = User::getAllByRole('professor');
                include __DIR__ . '/../views/admin/professors.php';
                break;

            case 'admin.saveProfessor':
                $this->saveProfessor();
                break;

            case 'admin.deleteProfessor':
                $this->deleteProfessor();
                break;

            // ================= Enrollment =================
            case 'admin.enroll':
                $studentId = (int)($_GET['student_id'] ?? 0);
                $student = User::getById($studentId);
                if (!$student) {
                    flash('danger', 'Student not found');
                    header('Location: index.php?page=admin.students');
                    exit;
                }
                $allSemesters = Semester::all();
                $enrolledIds = Enrollment::getSemesterIds($studentId);
                include __DIR__ . '/../views/admin/enroll.php';
                break;

            case 'admin.saveEnrollments':
                $this->saveEnrollments();
                break;

            // ================= Assignments =================
            case 'admin.assignments':
                $professors = User::getAllByRole('professor');
                $semesters = Semester::all();
                $db = getDB();
                $courses = $db->query("SELECT c.id, c.name, s.label AS semester_label FROM courses c JOIN semesters s ON c.semester_id = s.id ORDER BY s.id, c.name")->fetchAll();
                $assignmentsList = $db->query("SELECT a.id, u.name AS professor_name, c.name AS course_name, s.label AS semester_label, s.academic_year FROM assignments a JOIN users u ON a.professor_id = u.id JOIN courses c ON a.course_id = c.id JOIN semesters s ON a.semester_id = s.id ORDER BY s.id, c.name")->fetchAll();
                include __DIR__ . '/../views/admin/assignments.php';
                break;

            case 'admin.saveAssignment':
                $this->saveAssignment();
                break;

            case 'admin.deleteAssignment':
                $this->deleteAssignment();
                break;

            // ================= Semesters =================
            case 'admin.semesters':
                $semesters = Semester::all();
                include __DIR__ . '/../views/admin/semesters.php';
                break;

            case 'admin.saveSemester':
                $this->saveSemester();
                break;

            case 'admin.toggleSemester':
                $this->toggleSemester();
                break;

            case 'admin.deleteSemester':
                $this->deleteSemester();
                break;

            // ================= Courses =================
            case 'admin.courses':
                $semesters = Semester::all();
                $selectedSemester = $_GET['semester_id'] ?? '';
                $courses = $selectedSemester ? Course::getBySemester($selectedSemester) : [];
                include __DIR__ . '/../views/admin/courses.php';
                break;

            case 'admin.saveCourse':
                $this->saveCourse();
                break;

            case 'admin.deleteCourse':
                $this->deleteCourse();
                break;

            default:
                echo "Page not found";
        }
    }

    // ================= Semester Functions =================
    private function saveSemester() {
        requireRole('admin');
        $label = sanitize($_POST['label']);
        $year = sanitize($_POST['academic_year']);
        $id = $_POST['id'] ?? null;
        if ($id) Semester::update($id, $label, $year);
        else Semester::create($label, $year);
        flash('success', 'Semester saved.');
        header('Location: index.php?page=admin.semesters');
        exit;
    }

    private function toggleSemester() {
        requireRole('admin');
        $id = (int)$_POST['id'];
        Semester::setAllInactive();
        Semester::setActive($id);
        flash('success', 'Active semester updated.');
        header('Location: index.php?page=admin.semesters');
        exit;
    }

    private function deleteSemester() {
        requireRole('admin');
        $id = (int)$_POST['id'];
        if (Semester::countBySemester($id) > 0) {
            flash('danger', 'Cannot delete: semester has linked courses.');
        } else {
            Semester::delete($id);
            flash('success', 'Semester deleted.');
        }
        header('Location: index.php?page=admin.semesters');
        exit;
    }

    // ================= Course Functions =================
    private function saveCourse() {
        requireRole('admin');
        $name = sanitize($_POST['name']);
        $credits = (int)$_POST['credits'];
        $semesterId = (int)$_POST['semester_id'];
        $id = $_POST['id'] ?? null;
        if ($credits <= 0) {
            flash('danger', 'Credits must be positive.');
            header('Location: index.php?page=admin.courses&semester_id=' . $semesterId);
            exit;
        }
        if ($id) Course::update($id, $name, $credits, $semesterId);
        else Course::create($name, $credits, $semesterId);
        flash('success', 'Course saved.');
        header('Location: index.php?page=admin.courses&semester_id=' . $semesterId);
        exit;
    }

    private function deleteCourse() {
        requireRole('admin');
        $id = (int)$_POST['id'];
        if (Grade::countByCourse($id) > 0) {
            flash('danger', 'Cannot delete: grades exist for this course.');
        } else {
            Course::delete($id);
            flash('success', 'Course deleted.');
        }
        header('Location: index.php?page=admin.courses');
        exit;
    }

    // ================= Professor Functions =================
    private function saveProfessor() {
        requireRole('admin');
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $password = $_POST['password'] ?? '';
        $id = $_POST['id'] ?? null;

        if (User::emailExists($email, $id)) {
            flash('danger', 'Email already in use.');
            header('Location: index.php?page=admin.professors');
            exit;
        }

        if ($id) {
            User::update($id, $name, $email);
            if (!empty($password)) {
                User::updatePassword($id, password_hash($password, PASSWORD_BCRYPT));
            }
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            User::create($name, $email, $hash, 'professor');
        }
        flash('success', 'Professor saved.');
        header('Location: index.php?page=admin.professors');
        exit;
    }

    private function deleteProfessor() {
        requireRole('admin');
        $id = (int)$_POST['id'];
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM assignments WHERE professor_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            flash('danger', 'Cannot delete: professor has assignments.');
        } else {
            User::delete($id);
            flash('success', 'Professor deleted.');
        }
        header('Location: index.php?page=admin.professors');
        exit;
    }

    // ================= Student Functions =================
    private function saveStudent() {
        requireRole('admin');
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $password = $_POST['password'] ?? '';
        $id = $_POST['id'] ?? null;

        if (User::emailExists($email, $id)) {
            flash('danger', 'Email already in use.');
            header('Location: index.php?page=admin.students');
            exit;
        }

        if ($id) {
            User::update($id, $name, $email);
            if (!empty($password)) {
                User::updatePassword($id, password_hash($password, PASSWORD_BCRYPT));
            }
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            User::create($name, $email, $hash, 'student');
        }
        flash('success', 'Student saved.');
        header('Location: index.php?page=admin.students');
        exit;
    }

    private function deleteStudent() {
        requireRole('admin');
        $id = (int)$_POST['id'];
        $db = getDB();
        $db->beginTransaction();
        try {
            $db->prepare("DELETE FROM grades WHERE student_id = ?")->execute([$id]);
            $db->prepare("DELETE FROM gpa_records WHERE student_id = ?")->execute([$id]);
            $db->prepare("DELETE FROM enrollments WHERE student_id = ?")->execute([$id]);
            User::delete($id);
            $db->commit();
            flash('success', 'Student and related data deleted.');
        } catch (Exception $e) {
            $db->rollBack();
            flash('danger', 'Error deleting student.');
        }
        header('Location: index.php?page=admin.students');
        exit;
    }

    // ================= Enrollment Functions =================
    private function saveEnrollments() {
        requireRole('admin');
        $studentId = (int)$_POST['student_id'];
        $newIds = $_POST['semester_ids'] ?? [];
        $currentIds = Enrollment::getSemesterIds($studentId);

        $toAdd = array_diff($newIds, $currentIds);
        $toRemove = array_diff($currentIds, $newIds);

        $warnings = [];
        foreach ($toAdd as $semId) {
            Enrollment::create($studentId, $semId);
        }
        foreach ($toRemove as $semId) {
            $db = getDB();
            $stmt = $db->prepare("SELECT COUNT(*) FROM grades WHERE student_id=? AND semester_id=?");
            $stmt->execute([$studentId, $semId]);
            if ($stmt->fetchColumn() > 0) {
                $warnings[] = "Cannot remove from semester $semId (grades exist)";
            } else {
                Enrollment::delete($studentId, $semId);
            }
        }

        $msg = 'Enrollments updated.';
        if ($warnings) $msg .= ' Warnings: ' . implode(', ', $warnings);
        flash(count($warnings) ? 'warning' : 'success', $msg);
        header('Location: index.php?page=admin.enroll&student_id=' . $studentId);
        exit;
    }

    // ================= Assignment Functions =================
    private function saveAssignment() {
        requireRole('admin');
        $profId = (int)$_POST['professor_id'];
        $courseId = (int)$_POST['course_id'];
        $semId = (int)$_POST['semester_id'];

        if (Assignment::courseAlreadyAssigned($courseId, $semId)) {
            flash('danger', 'This course already has a professor for this semester.');
            header('Location: index.php?page=admin.assignments');
            exit;
        }
        Assignment::create($profId, $courseId, $semId);
        flash('success', 'Assignment saved.');
        header('Location: index.php?page=admin.assignments');
        exit;
    }

    private function deleteAssignment() {
        requireRole('admin');
        $assId = (int)$_POST['assignment_id'];
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM assignments WHERE id = ?");
        $stmt->execute([$assId]);
        flash('success', 'Assignment removed.');
        header('Location: index.php?page=admin.assignments');
        exit;
    }
}