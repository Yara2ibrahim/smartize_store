<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// التحقق من الطلب ونوع العملية
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['product_id'])) {
    $productId = (int)$_POST['product_id'];
    $action = $_POST['action'];

    // التحقق من وجود السلة
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // البحث عن المنتج في السلة
    $itemIndex = null;
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['product_id'] === $productId) {
            $itemIndex = $index;
            break;
        }
    }

    // تنفيذ العملية المطلوبة
    if ($itemIndex !== null) {
        switch ($action) {
            case 'increase':
                $_SESSION['cart'][$itemIndex]['quantity'] += 1;
                break;
            case 'decrease':
                if ($_SESSION['cart'][$itemIndex]['quantity'] > 1) {
                    $_SESSION['cart'][$itemIndex]['quantity'] -= 1;
                }
                break;
            case 'remove':
                unset($_SESSION['cart'][$itemIndex]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // إعادة ترقيم المصفوفة
                break;
        }
    }

    // إعادة توجيه المستخدم إلى صفحة السلة
    header('Location: cart.php');
    exit;
}

// إذا تم الوصول إلى الصفحة مباشرة بدون بيانات صحيحة
header('Location: cart.php');
exit;