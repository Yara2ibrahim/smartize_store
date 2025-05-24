
<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';


error_reporting(E_ALL); ini_set('display_errors', 1);

// التحقق من اتصال قاعدة البيانات
if(!$pdo) {
    die("Database connection failed");
}

// التحقق من وجود معرف المنتج
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$productId = (int)$_GET['id'];
$product = getProductDetails($pdo, $productId);

if (!$product) {
    header("Location: products.php");
    exit;
}

// في قسم الحصول على الصور الإضافية
$additionalImages = [];
$categoryDir = str_replace('_', ' ', $product['category_name']);
$productDir = str_replace(' ', '_', $product['product_name']);
$productFolder = "images/{$categoryDir}/{$productDir}/";

if (is_dir($productFolder)) {
    $files = scandir($productFolder);
    $allowedExtensions = ['.webp', '.jpg', '.jpeg', '.png'];
    
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $fileName = strtolower(pathinfo($file, PATHINFO_FILENAME));
            
            // استبعاد الملفات التي تبدأ بـ 'main' (الصورة الرئيسية)
            if (strpos($fileName, 'main') === false && in_array('.' . $fileExt, $allowedExtensions)) {
                $additionalImages[] = $productFolder . $file;
            }
        }
    }
    
    // ترتيب الصور حسب الزوايا (angle1, angle2, etc.)
    usort($additionalImages, function($a, $b) {
        preg_match('/angle(\d+)/i', $a, $matchesA);
        preg_match('/angle(\d+)/i', $b, $matchesB);
        
        $numA = isset($matchesA[1]) ? (int)$matchesA[1] : 0;
        $numB = isset($matchesB[1]) ? (int)$matchesB[1] : 0;
        
        return $numA - $numB;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['product_name']) ?> - Smartize Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .product-gallery {
            margin-bottom: 30px;
        }
        .main-image img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .thumbnails {
            margin-top: 15px;
        }
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .thumbnail:hover {
            border-color: #0d6efd;
        }
    </style>

</head>
<body>

    <?php include 'includes/them/header.php'; ?>

    <main class="container my-5">
        <div class="row">
   <div class="row">
    <div class="col-lg-6">
        <div class="main-product-image mb-4 text-center">
            <img src="<?= htmlspecialchars($product['main_image']) ?>" 
                 class="img-fluid rounded main-img"
                 alt="<?= htmlspecialchars($product['product_name']) ?>"
                 id="mainProductImage">
        </div>
        
        <?php if (!empty($product['all_images'])): ?>
        <div class="product-gallery">
            <div class="row g-2">
                <div class="col-3">
                    <img src="<?= htmlspecialchars($product['main_image']) ?>" 
                         class="img-thumbnail active"
                         onclick="changeMainImage(this.src)">
                </div>
                <?php foreach ($product['all_images'] as $image): ?>
                <div class="col-3">
                    <img src="<?= htmlspecialchars($image) ?>" 
                         class="img-thumbnail"
                         onclick="changeMainImage(this.src)">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="col-lg-6">
        <!-- تفاصيل المنتج -->
          <h1><?= htmlspecialchars($product['product_name']) ?></h1>
                <h3 class="text-success my-4">$<?= number_format($product['price'], 2) ?></h3>
                
                <div class="mb-4">
                    <h4>Description</h4>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
                
                <form action="add_to_cart.php" method="post" class="mt-4">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <select class="form-select" id="quantity" name="quantity">
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                          <form action="add_to_cart.php" method="post">
    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-shopping-cart"></i> Add to Cart
    </button>
</form>
                </form>
            </div>
        </div>

    </main>

    <?php include 'includes/them/footer.php'; ?>
<script>
function changeMainImage(src) {
    document.getElementById('mainProductImage').src = src;
    document.querySelectorAll('.img-thumbnail').forEach(el => {
        el.classList.remove('active');
    });
    event.target.classList.add('active');
}
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        });
    </script>
</body>
</html>