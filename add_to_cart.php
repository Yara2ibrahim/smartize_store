<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    try {
        // جلب بيانات المنتج من قاعدة البيانات
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$_POST['product_id']]);
        $product = $stmt->fetch();
        
        if (!$product) {
            throw new Exception("Product not found");
        }

        // إعداد بيانات المنتج للسلة
        $cart_item = [
            'product_id' => $product['id'],
            'product_name' => $product['product_name'],
            'price' => $product['price'],
            'quantity' => $_POST['quantity'] ?? 1,
            'image' => $product['main_image'] ?? 'images/default.jpg'
        ];

        // إنشاء السلة إذا لم تكن موجودة
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // التحقق من وجود المنتج في السلة
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $cart_item['product_id']) {
                $item['quantity'] += $cart_item['quantity'];
                $found = true;
                break;
            }
        }

        // إضافة المنتج إذا لم يكن موجوداً
        if (!$found) {
            $_SESSION['cart'][] = $cart_item;
        }

        // توجيه المستخدم إلى صفحة السلة
        header("Location: cart.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else {
    header("Location: products.php");
    exit;
}