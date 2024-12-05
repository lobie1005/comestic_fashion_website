// Cart data structure
let cart = {
    items: [],
    subtotal: 0,
    shipping: 0,
    total: 0
};

// Constants
const SHIPPING_THRESHOLD = 50; // Free shipping above this amount
const SHIPPING_COST = 5; // Standard shipping cost

// Load cart from localStorage on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    updateCartDisplay();
});

// Add item to cart
function addToCart(productId, productName, price, quantity = 1) {
    // Load current cart
    loadCart();
    
    // Check if item already exists
    const existingItem = cart.items.find(item => item.productId === productId);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.items.push({
            productId: productId,
            name: productName,
            price: parseFloat(price),
            quantity: quantity,
            image: `uploads/products/${productId}.jpg` // Default image path
        });
    }
    
    // Save and update display
    saveCart();
    updateCartDisplay();
    
    // Show success message
    showToast('Product added to cart successfully!');
}

// Update item quantity
function updateQuantity(productId, newQuantity) {
    const item = cart.items.find(item => item.productId === productId);
    if (item) {
        item.quantity = Math.max(1, newQuantity); // Ensure quantity is at least 1
        saveCart();
        updateCartDisplay();
    }
}

// Remove item from cart
function removeFromCart(productId) {
    cart.items = cart.items.filter(item => item.productId !== productId);
    saveCart();
    updateCartDisplay();
}

// Clear entire cart
function clearCart() {
    if (confirm('Are you sure you want to clear your cart?')) {
        cart.items = [];
        saveCart();
        updateCartDisplay();
    }
}

// Calculate cart totals
function calculateTotals() {
    cart.subtotal = cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    cart.shipping = cart.subtotal >= SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
    cart.total = cart.subtotal + cart.shipping;
}

// Save cart to localStorage
function saveCart() {
    calculateTotals();
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartBadge();
}

// Load cart from localStorage
function loadCart() {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
    }
}

// Update cart display
function updateCartDisplay() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartSummaryContainer = document.getElementById('cart-summary');
    const checkoutButton = document.getElementById('checkout-button');
    
    if (!cartItemsContainer) return; // Not on cart page
    
    // Clear current display
    cartItemsContainer.innerHTML = '';
    
    if (cart.items.length === 0) {
        cartItemsContainer.innerHTML = '<p class="text-center py-4">Your cart is empty.</p>';
        checkoutButton.disabled = true;
        return;
    }
    
    // Enable checkout button
    checkoutButton.disabled = false;
    
    // Add items
    cart.items.forEach(item => {
        const template = document.getElementById('cart-item-template');
        const clone = template.content.cloneNode(true);
        
        // Set item data
        const cartItem = clone.querySelector('.cart-item');
        cartItem.dataset.productId = item.productId;
        
        // Set image
        const image = clone.querySelector('img');
        image.src = item.image;
        image.alt = item.name;
        
        // Set other details
        clone.querySelector('.product-name').textContent = item.name;
        clone.querySelector('.product-price').textContent = item.price.toFixed(2);
        clone.querySelector('.quantity-input').value = item.quantity;
        clone.querySelector('.item-total').textContent = (item.price * item.quantity).toFixed(2);
        
        // Add event listeners
        clone.querySelector('.quantity-decrease').addEventListener('click', () => {
            updateQuantity(item.productId, item.quantity - 1);
        });
        
        clone.querySelector('.quantity-increase').addEventListener('click', () => {
            updateQuantity(item.productId, item.quantity + 1);
        });
        
        clone.querySelector('.quantity-input').addEventListener('change', (e) => {
            updateQuantity(item.productId, parseInt(e.target.value) || 1);
        });
        
        clone.querySelector('.remove-item').addEventListener('click', () => {
            removeFromCart(item.productId);
        });
        
        cartItemsContainer.appendChild(clone);
    });
    
    // Update summary
    if (cartSummaryContainer) {
        const template = document.getElementById('cart-summary-template');
        const clone = template.content.cloneNode(true);
        
        clone.querySelector('.cart-subtotal').textContent = cart.subtotal.toFixed(2);
        clone.querySelector('.shipping-cost').textContent = cart.shipping.toFixed(2);
        clone.querySelector('.cart-total').textContent = cart.total.toFixed(2);
        
        cartSummaryContainer.innerHTML = '';
        cartSummaryContainer.appendChild(clone);
    }
}

// Update cart badge in header
function updateCartBadge() {
    const badge = document.getElementById('cart-badge');
    if (badge) {
        const itemCount = cart.items.reduce((sum, item) => sum + item.quantity, 0);
        badge.textContent = itemCount;
        badge.style.display = itemCount > 0 ? 'inline' : 'none';
    }
}

// Show toast message
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast show position-fixed bottom-0 end-0 m-3';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="toast-header">
            <strong class="me-auto">Cart</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}