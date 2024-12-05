<?php
// Check if user is logged in
if (!isset($_SESSION[SESSION_USER_ID])) {
    header("Location: " . BASE_URL . "/login.php");
    exit();
}

require_once 'includes/header.php';
require_once 'models/ProductImage.php';

$productImage = new ProductImage($db);
?>

<div class="container my-5">
    <h1 class="mb-4">Shopping Cart</h1>

    <div class="row">
        <div class="col-lg-8">
            <!-- Cart Items -->
            <div class="card mb-4">
                <div class="card-body">
                    <div id="cart-items" aria-live="polite">
                        <!-- Cart items will be loaded here via JavaScript -->
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Cart Summary -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Cart Summary</h5>
                    <div id="cart-summary">
                        <!-- Summary will be loaded here via JavaScript -->
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-primary" onclick="window.location.href='checkout.php'" id="checkout-button" disabled>
                            Proceed to Checkout
                        </button>
                        <button class="btn btn-outline-danger" onclick="clearCart()">
                            Clear Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cart Item Template -->
<template id="cart-item-template">
    <div class="cart-item mb-3" data-product-id="">
        <div class="row align-items-center">
            <div class="col-md-2">
                <img src="" alt="Product Image" class="img-fluid rounded">
            </div>
            <div class="col-md-4">
                <h5 class="product-name mb-1"></h5>
                <p class="text-muted mb-0">Price: $<span class="product-price"></span></p>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <button class="btn btn-outline-secondary quantity-decrease" type="button">-</button>
                    <input type="number" class="form-control text-center quantity-input" value="1" min="1">
                    <button class="btn btn-outline-secondary quantity-increase" type="button">+</button>
                </div>
            </div>
            <div class="col-md-2">
                <p class="text-end mb-0">$<span class="item-total"></span></p>
            </div>
            <div class="col-md-1">
                <button class="btn btn-link text-danger remove-item" title="Remove Item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Cart Summary Template -->
<template id="cart-summary-template">
    <div class="summary-item d-flex justify-content-between mb-2">
        <span>Subtotal:</span>
        <span>$<span class="cart-subtotal">0.00</span></span>
    </div>
    <div class="summary-item d-flex justify-content-between mb-2">
        <span>Shipping:</span>
        <span>$<span class="shipping-cost">0.00</span></span>
    </div>
    <hr>
    <div class="summary-item d-flex justify-content-between mb-0">
        <strong>Total:</strong>
        <strong>$<span class="cart-total">0.00</span></strong>
    </div>
</template>

<script src="assets/js/cart.js"></script>
<?php require_once 'includes/footer.php'; ?>