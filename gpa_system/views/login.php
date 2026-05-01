<?php
// views/login.php
// صفحة تسجيل الدخول الرئيسية
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | نظام GPA</title>
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- خط عربي عصري -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background: radial-gradient(circle at 30% 30%, #1a1a2e, #16213e, #0f3460);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow-x: hidden;
        }
        /* دوائر خلفية متحركة */
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            animation: float 20s infinite ease-in-out;
            z-index: 0;
        }
        .bg-circle.one {
            width: 400px;
            height: 400px;
            top: -100px;
            left: -100px;
        }
        .bg-circle.two {
            width: 300px;
            height: 300px;
            bottom: -50px;
            right: -50px;
            animation-duration: 25s;
        }
        .bg-circle.three {
            width: 200px;
            height: 200px;
            top: 40%;
            left: 60%;
            animation-duration: 15s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1000px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 2rem 4rem rgba(0,0,0,0.5), inset 0 0 20px rgba(255,255,255,0.05);
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
        }
        .form-section {
            flex: 1 1 55%;
            padding: 3rem 2.5rem;
            backdrop-filter: blur(10px);
        }
        .info-section {
            flex: 1 1 45%;
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        @media (max-width: 768px) {
            .glass-card {
                flex-direction: column;
            }
            .info-section {
                border-right: none;
                border-top: 1px solid rgba(255,255,255,0.1);
            }
        }
        .brand-icon {
            font-size: 3rem;
            color: #e94560;
            margin-bottom: 0.25rem;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.07); color: #ff6b6b; }
            100% { transform: scale(1); }
        }
        .form-title {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .input-group-custom {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .input-group-custom .icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.6);
            z-index: 2;
            font-size: 1.2rem;
        }
        .custom-input {
            width: 100%;
            padding: 0.9rem 3rem 0.9rem 1rem;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s;
            outline: none;
        }
        .custom-input:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #e94560;
            box-shadow: 0 0 15px rgba(233, 69, 96, 0.3);
        }
        .custom-input::placeholder {
            color: rgba(255,255,255,0.4);
        }
        .btn-login {
            background: linear-gradient(135deg, #e94560, #c23152);
            border: none;
            border-radius: 1rem;
            padding: 0.85rem;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 1px;
            transition: all 0.3s;
            color: #fff;
            width: 100%;
            margin-top: 0.5rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn-login::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 60%);
            transition: 0.5s;
            opacity: 0;
            z-index: -1;
        }
        .btn-login:hover::after {
            opacity: 1;
            transform: scale(1.2);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 1rem 2rem rgba(233, 69, 96, 0.4);
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            transition: 0.3s;
        }
        .feature-item:hover {
            transform: translateX(-5px);
        }
        .feature-item .fi-icon {
            font-size: 1.8rem;
            color: #e94560;
            background: rgba(255,255,255,0.1);
            padding: 0.4rem;
            border-radius: 0.8rem;
        }
        .feature-item h6 {
            margin-bottom: 0.1rem;
            color: #fff;
            font-weight: 700;
        }
        .feature-item small {
            color: rgba(255,255,255,0.7);
        }

        .demo-badge {
            background: rgba(255,255,255,0.15);
            border-radius: 0.7rem;
            padding: 0.15rem 0.8rem;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 0.3rem;
        }
        .demo-email {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
        }
        .alert-danger {
            background: rgba(255,0,0,0.15);
            border: 1px solid rgba(255,0,0,0.3);
            color: #ffaaaa;
            border-radius: 0.8rem;
        }
        .quick-login {
            transition: all 0.3s;
        }
        .quick-login:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
    </style>
</head>
<body>
<div class="bg-circle one"></div>
<div class="bg-circle two"></div>
<div class="bg-circle three"></div>

<div class="login-wrapper">
    <div class="glass-card">
        <!-- قسم المعلومات / الميزات -->
        <div class="info-section">
            <h3 class="fw-bold mb-3"><i class="bi bi-mortarboard-fill me-2" style="color:#e94560;"></i>نظام GPA</h3>
            <p class="mb-4" style="color: rgba(255,255,255,0.8);">منصة أكاديمية متكاملة وآمنة لإدارة الدرجات والمعدلات، مبنية باستخدام MVC و AJAX.</p>
            <div class="feature-item">
                <div class="fi-icon"><i class="bi bi-shield-lock"></i></div>
                <div><h6>المدير</h6><small>إدارة الفصول، المواد، الأساتذة والطلاب</small></div>
            </div>
            <div class="feature-item">
                <div class="fi-icon"><i class="bi bi-pencil-square"></i></div>
                <div><h6>الأستاذ</h6><small>إدخال الدرجات عبر واجهة AJAX تفاعلية</small></div>
            </div>
            <div class="feature-item">
                <div class="fi-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <div><h6>الطالب</h6><small>عرض الدرجات والمعدل وتصدير CSV</small></div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <div class="mt-3">
                <small class="fw-bold" style="color: rgba(255,255,255,0.9);">حسابات تجريبية:</small>
                <div class="mt-2">
                    <span class="demo-badge bg-primary">مدير</span> <span class="demo-email">admin@school.com / password</span><br>
                    <span class="demo-badge bg-success">أستاذ</span> <span class="demo-email">prof@school.com / password</span><br>
                    <span class="demo-badge bg-warning text-dark">طالب</span> <span class="demo-email">student@school.com / password</span>
                </div>
            </div>
        </div>

        <!-- قسم تسجيل الدخول -->
        <div class="form-section text-center">
            <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <h2 class="form-title">تسجيل الدخول</h2>

            <?php if (function_exists('showFlash')) echo showFlash(); ?>

            <form method="post" action="index.php?page=login">
                <div class="input-group-custom">
                    <span class="icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="custom-input" placeholder="البريد الإلكتروني" required>
                </div>
                <div class="input-group-custom">
                    <span class="icon"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="custom-input" placeholder="كلمة المرور" required>
                </div>
                <button type="submit" class="btn btn-login"><i class="bi bi-box-arrow-in-left me-2"></i>دخول</button>
            </form>

            <!-- أيقونات تعبئة سريعة حسب الدور -->
            <div class="mt-4">
                <small class="d-block mb-2" style="color: rgba(255,255,255,0.6);">تسجيل دخول سريع:</small>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-sm btn-outline-light quick-login" 
                            data-email="admin@school.com" data-password="password">
                        <i class="bi bi-shield-lock me-1"></i> مدير
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light quick-login" 
                            data-email="prof@school.com" data-password="password">
                        <i class="bi bi-person-badge me-1"></i> أستاذ
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light quick-login" 
                            data-email="student@school.com" data-password="password">
                        <i class="bi bi-mortarboard me-1"></i> طالب
                    </button>
                </div>
            </div>

            <p class="mt-3" style="color: rgba(255,255,255,0.5); font-size: 0.85rem;">جميع الحقوق محفوظة &copy; 2026</p>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- سكريبت تعبئة الحقول تلقائياً عند الضغط على الأزرار -->
<script>
    document.querySelectorAll('.quick-login').forEach(btn => {
        btn.addEventListener('click', function() {
            const email = this.getAttribute('data-email');
            const password = this.getAttribute('data-password');
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = password;
        });
    });
</script>
</body>
</html>