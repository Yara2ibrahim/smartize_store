<?php
// ملف includes/db.php
$host = 'localhost';
$dbname = 'smartize_store_db';
$username = 'root'; // اسم المستخدم الافتراضي في XAMPP
$password = ''; // كلمة المرور الافتراضية في XAMPP فارغة

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (!$pdo) {
    die("فشل الاتصال بقاعدة البيانات");
}


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn; // أضف هذا السطر إذا كان ملف db.php هو عبارة عن function
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

