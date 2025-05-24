<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

$featuredProducts = getFeaturedProducts($pdo, 6);

error_reporting(E_ALL); 
ini_set('display_errors', 1);

// إنشاء رابط أساسي للموقع
$baseUrl = "http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smartize Store - Premium Tech Accessories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: url('<?= $baseUrl ?>/images/pexels-nguyendesigner-12470762.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 350px 0;
            text-align: center;
            margin-bottom: 50px;
        }
        .product-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .product-img-container {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .product-img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.05);
        }
        
        .card-body {
            padding: 1.5rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/them/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to Smartize Store</h1>
            <p class="lead">Discover the best tech accessories for your lifestyle</p>
            <a href="products.php" class="btn btn-primary btn-lg">Browse All Products</a>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Featured Products</h2>
            <div class="row g-4">
                <?php if (!empty($featuredProducts)): ?>
                    <?php foreach ($featuredProducts as $product): ?>
                        <?php
                        // استعلام للحصول على الصور الرئيسية للمنتج
                        $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? LIMIT 1");
                        $stmt->execute([$product['id']]);
                        $mainImage = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        // تحديد مسار الصورة
                        $imagePath = $mainImage ? $baseUrl . '/images/' . $mainImage['image_path'] : $baseUrl . '/images/default-product.jpg';
                        ?>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card product-card h-100 border-0 shadow-sm">
                                <div class="product-img-container bg-white p-3">
                                    <img src="<?= $imagePath ?>" 
                                         class="product-img img-fluid" 
                                         alt="<?= htmlspecialchars($product['product_name']) ?>"
                                         onerror="this.src='<?= $baseUrl ?>/images/default-product.jpg'">
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($product['product_name']) ?></h5>
                                    <div class="mb-2">
                                        <div class="rating text-warning">
                                            <?php 
                                            $rating = (float)($product['rating'] ?? 0);
                                            $fullStars = floor($rating);
                                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                            
                                            for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $fullStars): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php elseif ($hasHalfStar && $i == $fullStars + 1): ?>
                                                    <i class="fas fa-star-half-alt"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="text-muted">(<?= (int)($product['review_count'] ?? 0) ?> reviews)</small>
                                    </div>
                                    <p class="text-success fw-bold fs-5 mb-3">$<?= number_format($product['price'], 2) ?></p>
                                    <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-primary">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-info">No featured products available at the moment.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/them/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>