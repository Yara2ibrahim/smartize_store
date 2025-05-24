<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);

    if (empty($username)) {
        $errors[] = "Username is required";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }

    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "Email already exists";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, $role]);

        $_SESSION['message'] = "Registration successful! Please login.";
        redirect('login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smartize Store</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets2/css/style_log_reg.css">
    
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Registration Form -->
            <div class="form-box register">
                <form method="POST" action="register.php">
                    <h1>Registration</h1>
                    
                    <?php if (!empty($errors)): ?>
                        <div style="color: red; margin-bottom: 15px; animation: fadeIn 0.5s ease-in-out;">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="auth-input-box">
                        <i class='bx bxs-user'></i>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="auth-input-box">
                        <i class='bx bxs-envelope'></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="auth-input-box">
                        <i class='bx bxs-lock-alt'></i>
                        <input type="password" name="password" placeholder="Password (min 6 characters)" required>
                    </div>
                    <div class="auth-input-box">
                        <i class='bx bxs-lock-alt'></i>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="auth-btn">Register</button>
                    <p>or register with social platforms</p>
                    <div class="social-icons">
                        <a href="#"><i class='bx bxl-google'></i></a>
                        <a href="#"><i class='bx bxl-facebook'></i></a>
                        <a href="#"><i class='bx bxl-github'></i></a>
                        <a href="#"><i class='bx bxl-linkedin'></i></a>
                    </div>
                </form>
            </div>

            <!-- Toggle Section -->
            <div class="auth-toggle-box">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <a href="login.php" class="auth-btn">Login</a>
            </div>
        </div>
    </div>

    <script>
        // Add smooth page transitions
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('.auth-container').style.transform = 'scale(0.9)';
                document.querySelector('.auth-container').style.opacity = '0';
                
                setTimeout(() => {
                    window.location.href = this.href;
                }, 300);
            });
        });
        
        // Add form submission animation
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            document.querySelector('.auth-container').style.transform = 'scale(0.9)';
            document.querySelector('.auth-container').style.opacity = '0';
            
            setTimeout(() => {
                this.submit();
            }, 300);
        });
        
        // Add hover effects for mobile touch devices
        document.querySelectorAll('.auth-btn, .social-icons a').forEach(element => {
            element.addEventListener('touchstart', function() {
                this.classList.add('hover-effect');
            });
            
            element.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('hover-effect');
                }, 200);
            });
        });
    </script>
</body>
</html>