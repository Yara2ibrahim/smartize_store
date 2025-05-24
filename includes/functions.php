<?php

function sanitizeInput($data) {
    return $data !== null ? htmlspecialchars(trim($data)) : '';
}

function redirect($url) {
    header("Location: $url");
    exit;
}
function getProductMainImageUrl($pdo, $productId) {
    try {
        $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? LIMIT 1");
        $stmt->execute([$productId]);
        $image = $stmt->fetchColumn();
        return $image ?: 'images/default.jpg';
    } catch (PDOException $e) {
        error_log("Error getting main image: " . $e->getMessage());
        return 'images/default.jpg';
    }
}

function getLatestProducts($pdo, $limit = 6) {
    try {
        $sql = "SELECT p.*, 
                (SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) AS main_image 
                FROM products p 
                ORDER BY p.created_at DESC 
                LIMIT ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error getting latest products: " . $e->getMessage());
        return [];
    }
}

function getProductsByCategory($pdo, $category, $limit = null) {
    try {
        // الحصول على اسم الفئة من جدول categories
        $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
        $stmt->execute([$category]);
        $categoryName = $stmt->fetchColumn();
        
        if (!$categoryName) {
            return [];
        }

        $sql = "SELECT p.*, 
                (SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) AS main_image 
                FROM products p 
                WHERE p.category = ?";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$category]);
        $products = $stmt->fetchAll();
        
        // تعديل مسارات الصور لتتوافق مع هيكل المجلدات
        foreach ($products as &$product) {
            if ($product['main_image']) {
                $product['main_image'] = 'images/' . str_replace(' ', '_', $categoryName) . '/' . 
                                        str_replace(' ', '_', $product['product_name']) . '/' . 
                                        basename($product['main_image']);
            } else {
                $product['main_image'] = 'images/default.jpg';
            }
        }
        
        return $products;
    } catch (PDOException $e) {
        error_log("Error getting products by category: " . $e->getMessage());
        return [];
    }
}

function getCategories($pdo) {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function incrementProductViews($pdo, $productId) {
    try {
        $stmt = $pdo->prepare("UPDATE products SET view_count = view_count + 1 WHERE id = ?");
        $stmt->execute([$productId]);
        return true;
    } catch (PDOException $e) {
        error_log("Error in incrementProductViews: " . $e->getMessage());
        return false;
    }
}

function getCategoryIcon($categoryName) {
    $icons = [
        'Car_Accessories' => 'car',
        'Charging' => 'bolt',
        'Power_Bank' => 'battery-full',
        'Power_Strip' => 'plug',
        'Security_Camera' => 'camera',
        'TWS_Earbuds' => 'headphones'
    ];
    
    return $icons[$categoryName] ?? 'tag';
}




// تعديل دالة getFeaturedProducts
function getFeaturedProducts($pdo, $limit = 6) {
    try {
        $sql = "SELECT p.*, 
                c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON p.category = c.id
                ORDER BY RAND() 
                LIMIT :limit";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // معالجة مسارات الصور
        foreach ($products as &$product) {
            $categoryDir = str_replace('_', ' ', $product['category_name']);
            $productDir = str_replace(' ', '_', $product['product_name']);
            $productFolder = "images/{$categoryDir}/{$productDir}/";
            
            // البحث عن الصورة الرئيسية بأي امتداد
            $mainImageFound = false;
            $possibleExtensions = ['main.webp', 'main.jpg', 'main.jpeg', 'main.png'];
            
            foreach ($possibleExtensions as $imageFile) {
                if (file_exists($productFolder . $imageFile)) {
                    $product['main_image'] = $productFolder . $imageFile;
                    $mainImageFound = true;
                    break;
                }
            }
            
            if (!$mainImageFound) {
                $product['main_image'] = "images/default.jpg";
            }
        }
        
        return $products;
        
    } catch (PDOException $e) {
        error_log("Error in getFeaturedProducts: " . $e->getMessage());
        return [];
    }
}

// دالة مساعدة للعثور على الصورة الرئيسية
function findMainImage($productFolder) {
    // هذه الدالة لم تعد ضرورية حيث نستخدم الآن جدول product_images
    return 'images/default.jpg';
}

function getProductMainImage($pdo, $productId) {
    try {
        $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY id LIMIT 1");
        $stmt->execute([$productId]);
        $image = $stmt->fetchColumn();
        
        if ($image) {
            // تعديل المسار ليتوافق مع هيكل المجلدات
            $image = str_replace('\\', '/', $image); // تحويل المسارات Windows إلى Unix-style
            $image = ltrim($image, '/'); // إزالة أي شرطة مائلة في البداية
            
            // التحقق من وجود الصورة
            if (file_exists($image)) {
                return $image;
            }
            
            // إذا لم توجد في المسار المطلق، نبحث في مجلد images
            $relativePath = 'images/' . basename(dirname($image)) . '/' . basename($image);
            if (file_exists($relativePath)) {
                return $relativePath;
            }
        }
        
        return 'images/default.jpg';
    } catch (PDOException $e) {
        error_log("Error getting main image: " . $e->getMessage());
        return 'images/default.jpg';
    }
}

function getProductImages($pdo, $productId) {
    try {
        // 1. جلب بيانات المنتج الأساسية
        $productStmt = $pdo->prepare("
            SELECT p.*, c.name AS category_name 
            FROM products p 
            JOIN categories c ON p.category = c.id 
            WHERE p.id = ?
        ");
        $productStmt->execute([$productId]);
        $product = $productStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) return ['main' => 'images/default.jpg', 'angles' => []];
        
        // 2. إنشاء المسار الأساسي
        $basePath = 'images/' . 
                   str_replace(' ', '_', $product['category_name']) . '/' . 
                   str_replace(' ', '_', $product['product_name']) . '/';
        
        // 3. جلب الصور من قاعدة البيانات
        $imagesStmt = $pdo->prepare("
            SELECT image_path 
            FROM product_images 
            WHERE product_id = ? 
            ORDER BY 
                CASE 
                    WHEN image_path LIKE '%main%' THEN 1
                    WHEN image_path LIKE '%angle1%' THEN 2
                    WHEN image_path LIKE '%angle2%' THEN 3
                    ELSE 4
                END
        ");
        $imagesStmt->execute([$productId]);
        
        $images = $imagesStmt->fetchAll(PDO::FETCH_COLUMN);
        
        // 4. معالجة المسارات
        $mainImage = 'default.jpg';
        $additionalImages = [];
        
        foreach ($images as $image) {
            $fullPath = $basePath . basename($image);
            if (file_exists($fullPath)) {
                if (str_contains($image, 'main')) {
                    $mainImage = $fullPath;
                } else {
                    $additionalImages[] = $fullPath;
                }
            }
        }
        
        return [
            'main' => $mainImage,
            'angles' => $additionalImages
        ];
        
    } catch (PDOException $e) {
        error_log("Error getting product images: " . $e->getMessage());
        return [
            'main' => 'images/default.jpg',
            'angles' => []
        ];
    }
}
function getProductDetails(PDO $pdo, int $productId) {
    try {
        // التحقق من اتصال PDO
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException("يجب أن تكون المعلمة الأولى كائن PDO صالح");
        }

        $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name 
                             FROM products p
                             JOIN categories c ON p.category = c.id
                             WHERE p.id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            return false;
        }

        // جلب صور المنتج
        $images = getProductImagesFromDB($pdo, $productId);
        
        $product['main_image'] = !empty($images[0]['image_path']) ? 
                               'images/'.$images[0]['image_path'] : 
                               'images/default.jpg';
        
        $product['all_images'] = array_map(function($img) {
            return 'images/'.$img['image_path'];
        }, array_slice($images, 1));

        return $product;

    } catch (PDOException $e) {
        error_log("خطأ في قاعدة البيانات: ".$e->getMessage());
        return false;
    }
}

function getAllProducts($pdo, $category = null) {
    try {
        // استعلام أكثر دقة مع JOIN صحيح
        $sql = "SELECT p.id, p.product_name, p.price, p.description,
                       c.name AS category_name,
                       (SELECT image_path FROM product_images 
                        WHERE product_id = p.id LIMIT 1) AS main_image_path
                FROM products p
                JOIN categories c ON p.category = c.id";
        
        if ($category) {
            $sql .= " WHERE c.name = :category";
        }
        
        $stmt = $pdo->prepare($sql);
        
        if ($category) {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // معالجة مسارات الصور
        foreach ($products as &$product) {
            $product['main_image'] = $product['main_image_path'] 
                ? 'images/' . $product['main_image_path'] 
                : 'images/default.jpg';
            
            // جلب الصور الإضافية
            $stmt = $pdo->prepare("SELECT image_path 
                                  FROM product_images 
                                  WHERE product_id = ? 
                                  ORDER BY id LIMIT 1, 10"); // تخطي الصورة الأولى
            $stmt->execute([$product['id']]);
            $product['all_images'] = array_map(function($img) {
                return 'images/' . $img['image_path'];
            }, $stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        
        return $products;
    } catch (PDOException $e) {
        error_log("Error in getAllProducts: " . $e->getMessage());
        return [];
    }
}
function getProductImagesFromDB($pdo, $productId) {
    try {
        $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY id");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting product images from DB: " . $e->getMessage());
        return [];
    }
}

?>

