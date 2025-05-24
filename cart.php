<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// تحقق من وجود اتصال
if (!isset($conn)) {
    die("Database connection not established");
}

include 'includes/them/header.php';

// التحقق من اتصال PDO
if (!($conn instanceof PDO)) {
    die("Invalid database connection");
}

// تهيئة المتغيرات
$cartItems = $_SESSION['cart'] ?? [];
$subtotal = 0;
$total = 0;
$shippingCost = 0;

// حساب فقط إذا كانت السلة غير فارغة
if (!empty($cartItems)) {
    foreach ($cartItems as $item) {
        $product = getProductDetails($conn, $item['product_id']);
        if ($product) {
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;
        }
    }

    // حساب تكلفة الشحن فقط إذا كان هناك منتجات
    $shippingCost = max(5, $subtotal * 0.10);
    $total = $subtotal + $shippingCost;
}

// إضافة تكلفة الشحن (مثال: 10% من المجموع بحد أدنى 5$)
$shippingCost = max(5, $subtotal * 0.10);
$total += $shippingCost;
?>

<div class="checkout-wrapper container py-5">
  <div class="row">
    <!-- Left Section - معلومات الشحن والدفع -->
    <div class="col-md-7">
      <form id="checkoutForm" method="post" action="process_order.php">
        <!-- معلومات الاتصال -->
        <h5 class="mb-4">Contact Information</h5>
        <div class="mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email" required>
          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="newsletter" checked>
            <label class="form-check-label">Email me with news and offers</label>
          </div>
        </div>

        <!-- معلومات الشحن -->
        <h5 class="mb-4">Shipping Address</h5>
        <div class="row">
          <div class="col-md-6 mb-3">
            <input type="text" class="form-control" name="first_name" placeholder="First name" required>
          </div>
          <div class="col-md-6 mb-3">
            <input type="text" class="form-control" name="last_name" placeholder="Last name" required>
          </div>
        </div>
        <div class="mb-3">
          <input type="text" class="form-control" name="address" placeholder="Address" required>
        </div>
        <div class="mb-3">
          <input type="text" class="form-control" name="apartment" placeholder="Apartment, suite, etc. (optional)">
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <input type="text" class="form-control" name="city" placeholder="City" required>
          </div>
          <div class="col-md-3 mb-3">
            <input type="text" class="form-control" name="state" placeholder="State" required>
          </div>
          <div class="col-md-3 mb-3">
            <input type="text" class="form-control" name="zip_code" placeholder="ZIP Code" required>
          </div>
        </div>
        <div class="mb-3">
          <input type="tel" class="form-control" name="phone" placeholder="Phone" required>
        </div>

        <!-- خيارات الدفع -->
        <h5 class="mb-4">Payment Method</h5>
        <div class="payment-options">
          <div class="form-check mb-3">
            <input class="form-check-input" type="radio" name="payment_method" id="creditCard" value="credit_card" checked>
            <label class="form-check-label" for="creditCard">
              <i class="fab fa-cc-visa"></i> <i class="fab fa-cc-mastercard"></i> Credit Card
            </label>
            <div id="creditCardFields" class="mt-3">
              <div class="mb-3">
                <input type="text" class="form-control" name="card_number" placeholder="Card number">
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <input type="text" class="form-control" name="card_expiry" placeholder="MM/YY">
                </div>
                <div class="col-md-6 mb-3">
                  <input type="text" class="form-control" name="card_cvv" placeholder="CVV">
                </div>
              </div>
            </div>
          </div>

          <div class="form-check mb-3">
            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
            <label class="form-check-label" for="paypal">
              <i class="fab fa-paypal"></i> PayPal
            </label>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod">
            <label class="form-check-label" for="cod">
              <i class="fas fa-money-bill-wave"></i> Cash on Delivery
            </label>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">Complete Order</button>
      </form>
    </div>

    <!-- Right Section - ملخص السلة -->
    <!-- Right Section - ملخص السلة -->
<!-- قسم ملخص الطلب -->
<div class="col-md-5">
  <div class="cart-summary p-4 border rounded">
    <h5 class="mb-4">ملخص الطلب</h5>

    <?php if (empty($cartItems)): ?>
      <div class="alert alert-info">سلة التسوق فارغة</div>
    <?php else: ?>
      <?php foreach ($cartItems as $item): 
          $product = getProductDetails($conn, $item['product_id']);
          if ($product):
              $itemSubtotal = $item['price'] * $item['quantity'];
      ?>
      <div class="d-flex mb-3">
        <img src="<?= htmlspecialchars($product['main_image'] ?? 'images/default.jpg') ?>" 
             class="product-img me-3" 
             style="width: 80px; height: 80px; object-fit: cover;"
             alt="<?= htmlspecialchars($product['product_name']) ?>">
        <div class="flex-grow-1">
          <div><?= htmlspecialchars($product['product_name']) ?></div>
          <div class="d-flex align-items-center mt-2">
            <!-- زر تقليل الكمية -->
            <form action="update_cart.php" method="post" class="me-2">
              <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
              <input type="hidden" name="action" value="decrease">
              <button type="submit" class="btn btn-sm btn-outline-secondary">-</button>
            </form>
            
            <span class="mx-2"><?= $item['quantity'] ?></span>
            
            <!-- زر زيادة الكمية -->
            <form action="update_cart.php" method="post" class="me-2">
              <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
              <input type="hidden" name="action" value="increase">
              <button type="submit" class="btn btn-sm btn-outline-secondary">+</button>
            </form>
            
            <!-- زر الحذف -->
            <form action="update_cart.php" method="post">
              <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
              <input type="hidden" name="action" value="remove">
              <button type="submit" class="btn btn-sm btn-danger">
                <i class="fas fa-trash-alt"></i>
              </button>
            </form>
          </div>
          <strong class="d-block mt-2">$<?= number_format($itemSubtotal, 2) ?></strong>
        </div>
      </div>
      <?php 
          endif;
      endforeach; ?>

      <div class="mb-3">
        <input type="text" class="form-control" placeholder="كود الخصم">
        <button class="btn btn-outline-secondary mt-2 w-100">تطبيق</button>
      </div>

      <hr>
      <div class="d-flex justify-content-between mb-2">
        <span>المجموع الفرعي</span>
        <span>$<?= number_format($subtotal, 2) ?></span>
      </div>
      <div class="d-flex justify-content-between mb-2">
        <span>الشحن</span>
        <span>$<?= number_format($shippingCost, 2) ?></span>
      </div>
      <hr>
      <div class="d-flex justify-content-between fw-bold fs-5">
        <span>المجموع الكلي</span>
        <span>$<?= number_format($total, 2) ?></span>
      </div>
    <?php endif; ?>
  </div>
</div>
  </div>
</div>

<script>
// إظهار/إخفاء حقول بطاقة الائتمان حسب طريقة الدفع المختارة
document.querySelectorAll('input[name="payment_method"]').forEach(el => {
    el.addEventListener('change', function() {
        document.getElementById('creditCardFields').style.display = 
            this.value === 'credit_card' ? 'block' : 'none';
    });
});
</script>

<?php include 'includes/them/footer.php'; ?>