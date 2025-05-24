<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

error_reporting(E_ALL); 
ini_set('display_errors', 1);

// التحقق من اتصال قاعدة البيانات
if(!$pdo) {
    die("Database connection failed");
}

// الحصول على الفئة من URL


// الحصول على الفئة من URL
$category = isset($_GET['category']) ? $_GET['category'] : null;

// الحصول على المنتجات
$products = getAllProducts($pdo, $category);

// للتحقق من البيانات (يمكن إزالتها بعد التأكد من العمل)
error_log("Category: " . $category);
error_log("Products count: " . count($products));


// Debug: تسجيل بيانات المنتجات المسترجعة
error_log("Retrieved Products: " . print_r($products, true));

// التأكد من وجود صور افتراضية للمنتجات
foreach ($products as &$product) {
    if (!isset($product['main_image']) || !file_exists($product['main_image'])) {
        $product['main_image'] = 'images/default.jpg';
    }
    if (!isset($product['all_images'])) {
        $product['all_images'] = [];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category ? $category : 'All') ?> Products - Smartize Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .product-img-container {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
}

.product-main-img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    transition: transform 0.3s;
}

.product-img-container:hover .product-main-img {
    transform: scale(1.05);
}

.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
}

.main-product-image {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.main-img {
    max-height: 400px;
    max-width: 100%;
    object-fit: contain;
}

.img-thumbnail {
    cursor: pointer;
    transition: all 0.3s;
    height: 80px;
    object-fit: cover;
}

.img-thumbnail:hover {
    opacity: 0.8;
}

.img-thumbnail.active {
    border-color: #0d6efd;
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}



        .empty-store {
            text-align: center;
            padding: 50px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin: 30px 0;
        }
        .empty-store-icon {
            font-size: 60px;
            color: #6c757d;
            margin-bottom: 20px;
        }
        .category-list {
            list-style-type: none;
            padding: 0;
        }
        .category-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .category-list li a {
            text-decoration: none;
            color: #333;
        }
        .category-list li a:hover {
            color: #0d6efd;
        }
        .product-card {
            transition: all 0.3s ease;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .card-body {
            padding: 1.25rem;
        }
        .rating {
            color: #f59e0b;
        }
        .product-title {
            font-size: 1rem;
            height: 2.5rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
    </style>
</head>
<body>
    <?php include 'includes/them/header.php'; ?>

    <main class="container my-5">
        <div class="row">
                        <!-- قسم الفئات -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Categories</h5>
                    </div>
                    <div class="card-body">
                        <ul class="category-list">
                            <li><a href="products.php" class="<?= !$category ? 'fw-bold' : '' ?>"><i class="fas fa-th-large me-2"></i>All Categories</a></li>
                            <?php 
                            $categories = getCategories($pdo);
                            foreach ($categories as $cat): 
                                $isActive = $category == $cat['name'] ? 'fw-bold' : '';
                            ?>
                                <li>
                                    <a href="products.php?category=<?= urlencode($cat['name']) ?>" class="<?= $isActive ?>">
                                        <i class="fas fa-<?= getCategoryIcon($cat['name']) ?> me-2"></i>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-store me-2"></i>Smartize Store</h5>
                        <p class="card-text">Premium tech accessories for your everyday life.</p>
                    </div>
                </div>
            </div>
    

            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php if (!empty($products)): ?>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php foreach ($products as $product): ?>
    <div class="col">
        <div class="card h-100 product-card">
            <div class="product-img-container position-relative">
                <img src="<?= htmlspecialchars($product['main_image']) ?>" 
                     class="product-main-img"
                     alt="<?= htmlspecialchars($product['product_name']) ?>"
                     onerror="this.src='images/default.jpg'">
                
                <?php if (!empty($product['all_images'])): ?>
                <div class="product-badge">
                    <span class="badge bg-secondary">
                        <i class="fas fa-camera me-1"></i> <?= count($product['all_images']) + 1 ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                <div class="mb-2">
                    <span class="badge bg-primary">
                        <?= htmlspecialchars($product['category_name']) ?>
                    </span>
                </div>
                <p class="text-success fw-bold mb-3">$<?= number_format($product['price'], 2) ?></p>
                <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-primary w-100">
                    <i class="fas fa-eye me-1"></i> View Details
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
                        <?php else: ?>
                            <div class="alert alert-info text-center py-5">
                                <i class="fas fa-info-circle fa-3x mb-3"></i>
                                <h4>No products found</h4>
                                <a href="products.php" class="btn btn-primary mt-3">Browse All Products</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- معرض الصور المنبثق -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="productGallery" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="galleryImages"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productGallery" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productGallery" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/them/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function showGallery(productId) {
        const products = <?= json_encode($products) ?>;
        const product = products.find(p => p.id == productId);
        
        if (product) {
            document.getElementById('galleryModalTitle').textContent = product.product_name;
            const gallery = document.getElementById('galleryImages');
            gallery.innerHTML = '';
            
            const allImages = [product.main_image, ...(product.all_images || [])];
            
            allImages.forEach((img, index) => {
                const item = document.createElement('div');
                item.className = `carousel-item ${index === 0 ? 'active' : ''}`;
                item.innerHTML = `<img src="${img}" class="d-block w-100" alt="Product Image">`;
                gallery.appendChild(item);
            });
            
            const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
            modal.show();
        }
    }
    </script>
</body>
</html>