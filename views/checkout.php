<?php 
require_once 'includes/header.php';
require_once 'models/ProductImage.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout.php');
    exit;
}

$productImage = new ProductImage($db);
?>
<div class="container my-5">
    <h1 class="mb-4">Checkout</h1>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php 
        echo $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?php 
        echo $_SESSION['success'];
        unset($_SESSION['success']);
        ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Order Summary -->
        <div class="col-lg-4 order-lg-2 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>
                    <div id="checkout-items">
                        <!-- Items will be loaded here via JavaScript -->
                    </div>
                    <hr>
                    <div id="checkout-summary">
                        <!-- Summary will be loaded here via JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="col-lg-8 order-lg-1">
            <div class="card">
                <div class="card-body">
                    <form id="checkout-form" action="controllers/checkout_controller.php" method="POST">
                        <!-- Billing Information -->
                        <h5 class="mb-4">Billing Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required aria-label="First Name">
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required aria-label="Last Name">
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required aria-label="Email">
                            </div>
                            <div class="col-12">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required aria-label="Phone">
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <h5 class="mt-4 mb-4">Shipping Address</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="address" class="form-label">Street Address</label>
                                <input type="text" class="form-control" id="address" name="address" required aria-label="Street Address">
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" required aria-label="City">
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state" required aria-label="State">
                            </div>
                            <div class="col-md-2">
                                <label for="zip" class="form-label">ZIP Code</label>
                                <input type="text" class="form-control" id="zip" name="zip" required aria-label="ZIP Code">
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <h5 class="mt-4 mb-4">Payment Information</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="card_name" class="form-label">Name on Card</label>
                                <input type="text" class="form-control" id="card_name" name="card_name" required aria-label="Name on Card">
                            </div>
                            <div class="col-12">
                                <label for="card_number" class="form-label">Card Number</label>
                                <input type="text" class="form-control" id="card_number" name="card_number" required aria-label="Card Number">
                            </div>
                            <div class="col-md-4">
                                <label for="expiry_month" class="form-label">Expiry Month</label>
                                <select class="form-select" id="expiry_month" name="expiry_month" required aria-label="Expiry Month">
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?php echo sprintf('%02d', $i); ?>">
                                            <?php echo sprintf('%02d', $i); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="expiry_year" class="form-label">Expiry Year</label>
                                <select class="form-select" id="expiry_year" name="expiry_year" required aria-label="Expiry Year">
                                    <?php 
                                    $current_year = date('Y');
                                    for ($i = $current_year; $i <= $current_year + 10; $i++): 
                                    ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" name="cvv" required aria-label="CVV">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" aria-label="Place Order">Place Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Item Template -->
<template id="checkout-item-template">
    <div class="checkout-item d-flex align-items-center mb-3">
        <img src="" alt="Product Image" class="img-thumbnail me-3" style="width: 64px; height: 64px; object-fit: cover;">
        <div class="flex-grow-1">
            <h6 class="product-name mb-1"></h6>
            <div class="d-flex justify-content-between">
                <small class="text-muted">Qty: <span class="quantity"></span></small>
                <span class="item-total"></span>
            </div>
        </div>
    </div>
</template>

<!-- Checkout Summary Template -->
<template id="checkout-summary-template">
    <div class="summary-item d-flex justify-content-between mb-2">
        <span>Subtotal:</span>
        <span class="checkout-subtotal"></span>
    </div>
    <div class="summary-item d-flex justify-content-between mb-2">
        <span>Shipping:</span>
        <span class="checkout-shipping"></span>
    </div>
    <hr>
    <div class="summary-item d-flex justify-content-between mb-0">
        <strong>Total:</strong>
        <strong class="checkout-total"></strong>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load cart data
    const cart = JSON.parse(localStorage.getItem('cart')) || { items: [], subtotal: 0, shipping: 0, total: 0 };
    
    // Display checkout items
    const checkoutItemsContainer = document.getElementById('checkout-items');
    const itemTemplate = document.getElementById('checkout-item-template');
    
    cart.items.forEach(item => {
        const clone = itemTemplate.content.cloneNode(true);
        
        clone.querySelector('img').src = item.image;
        clone.querySelector('img').alt = item.name;
        clone.querySelector('.product-name').textContent = item.name;
        clone.querySelector('.quantity').textContent = item.quantity;
        clone.querySelector('.item-total').textContent = `$${(item.price * item.quantity).toFixed(2)}`;
        
        checkoutItemsContainer.appendChild(clone);
    });
    
    // Display summary
    const summaryContainer = document.getElementById('checkout-summary');
    const summaryTemplate = document.getElementById('checkout-summary-template');
    const summaryClone = summaryTemplate.content.cloneNode(true);
    
    summaryClone.querySelector('.checkout-subtotal').textContent = `$${cart.subtotal.toFixed(2)}`;
    summaryClone.querySelector('.checkout-shipping').textContent = `$${cart.shipping.toFixed(2)}`;
    summaryClone.querySelector('.checkout-total').textContent = `$${cart.total.toFixed(2)}`;
    
    summaryContainer.appendChild(summaryClone);
    
    // Add hidden input for cart data
    const form = document.getElementById('checkout-form');
    const cartInput = document.createElement('input');
    cartInput.type = 'hidden';
    cartInput.name = 'cart_data';
    cartInput.value = JSON.stringify(cart);
    form.appendChild(cartInput);
});
</script>

<?php require_once 'includes/footer.php'; ?>
