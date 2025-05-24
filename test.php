<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

echo '<pre>';
print_r(getAllProducts($pdo));
echo '</pre>';

foreach (getAllProducts($pdo) as $product) {
    echo '<img src="' . $product['main_image'] . '" style="max-width: 200px;"><br>';
    echo $product['product_name'] . '<br><br>';
}
?>