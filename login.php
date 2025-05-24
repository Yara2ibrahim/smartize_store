<?php
require_once 'includes/db.php'; // هذا الملف يبدأ الجلسة تلقائياً
require_once 'includes/functions.php';

$error = null; // تعريف متغير الخطأ مسبقاً

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    // تحضير الاستعلام بشكل أكثر أماناً
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // التحقق من وجود المستخدم أولاً ثم من صحة كلمة المرور
    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            
            // التوجيه بعد تسجيل الدخول
            if (isset($_SESSION['redirect_url'])) {
                $redirect_url = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']);
                redirect($redirect_url);
            } else {
                redirect('index.php');
            }
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
    // في جزء التحقق من صحة الدخول
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['username'] = $user['username'];
    
    // استدعاء الدالة الجديدة
    redirectAfterLogin();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smartize Store</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets2/css/style_log_reg.css">

</head>
<body>
    <div class="auth-container">
        <div class="auth-card" id="authContainer">
            <!-- نموذج تسجيل الدخول -->
            <div class="form-box login">
                <form method="POST" action="login.php">
                    <h1>Login</h1>
                    
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="auth-alert auth-alert-success">
                            <?php echo $_SESSION['message']; ?>
                            <?php unset($_SESSION['message']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="auth-alert auth-alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="auth-input-box">
                        <input type="email" name="email" placeholder="Email" required>
                        <i class='bx bxs-envelope'></i>
                    </div>
                    <div class="auth-input-box">
                        <input type="password" name="password" placeholder="Password" required>
                        <i class='bx bxs-lock-alt'></i>
                    </div>
                    <button type="submit" class="auth-btn">Login</button>
                    <p>or login with social platforms</p>
                    <div class="social-icons">
                        <a href="#"><i class='bx bxl-google'></i></a>
                        <a href="#"><i class='bx bxl-facebook'></i></a>
                        <a href="#"><i class='bx bxl-github'></i></a>
                        <a href="#"><i class='bx bxl-linkedin'></i></a>
                    </div>
                </form>
            </div>

            <!-- قسم التبديل -->
            <div class="auth-toggle-box">
                <div class="auth-toggle-panel toggle-left">
                    <h1>Hello, Welcome!</h1>
                    <p>Don't have an account?</p>
                    <button class="auth-btn" id="showRegister">Register</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('showRegister').addEventListener('click', () => {
            window.location.href = 'register.php';
        });
    </script>
</body>
</html>