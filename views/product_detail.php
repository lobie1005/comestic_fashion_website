<?php
require_once 'includes/header.php';
require_once 'models/Product.php';
require_once 'models/ProductImage.php';

$product = new Product($db);
$productImage = new ProductImage($db);

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    header('Location: index.php');
    exit;
}

// Get product data
$product_data = $product->getProductById($product_id);
if (!$product_data) {
    header('Location: index.php');
    exit;
}

// Get all images for the product
$product_images = $productImage->getProductImages($product_id);
$primary_image = $productImage->getPrimaryImage($product_id);

// If no images, use default
if (empty($product_images)) {
    $product_images = [['image_path' => 'default.jpg']];
}
?>

<div class="container my-5">
    <div class="row">
        <!-- Product Images Gallery -->
        <div class="col-md-6 mb-4">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($product_images as $index => $image): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="uploads/products/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                 class="d-block w-100" 
                                 alt="<?php echo htmlspecialchars($product_data['product_name']); ?>"
                                 style="height: 400px; object-fit: cover;"
                                 aria-labelledby="product-image-<?php echo $index; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($product_images) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Thumbnail Navigation -->
            <?php if (count($product_images) > 1): ?>
                <div class="d-flex mt-2 gap-2 thumbnail-gallery">
                    <?php foreach ($product_images as $index => $image): ?>
                        <div class="thumbnail" 
                             onclick="$('#productCarousel').carousel(<?php echo $index; ?>)"
                             style="cursor: pointer;">
                            <img src="uploads/products/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                 alt="Thumbnail"
                                 class="img-thumbnail"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="mb-4"><?php echo htmlspecialchars($product_data['product_name']); ?></h1>
            <p class="lead mb-4">$<?php echo number_format($product_data['price'], 2); ?></p>
            
            <div class="mb-4">
                <h5>Description</h5>
                <p><?php echo nl2br(htmlspecialchars($product_data['description'])); ?></p>
            </div>

            <div class="mb-4">
                <h5>Stock Status</h5>
                <?php if ($product_data['stock_quantity'] > 0): ?>
                    <p class="text-success">In Stock (<?php echo $product_data['stock_quantity']; ?> available)</p>
                <?php else: ?>
                    <p class="text-danger">Out of Stock</p>
                <?php endif; ?>
            </div>

            <?php if ($product_data['stock_quantity'] > 0): ?>
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="input-group" style="width: 130px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(-1)">-</button>
                        <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="<?php echo $product_data['stock_quantity']; ?>">
                        <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(1)">+</button>
                    </div>
                    <button class="btn btn-primary add-to-cart" 
                            data-product-id="<?php echo $product_data['product_id']; ?>"
                            data-product-name="<?php echo htmlspecialchars($product_data['product_name']); ?>"
                            data-product-price="<?php echo $product_data['price']; ?>">
                        Add to Cart
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function updateQuantity(change) {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value) || 1;
    const maxValue = parseInt(input.max);
    
    let newValue = currentValue + change;
    if (newValue < 1) newValue = 1;
    if (newValue > maxValue) newValue = maxValue;
    
    input.value = newValue;
}

// Update add to cart functionality to include quantity
document.querySelector('.add-to-cart').addEventListener('click', function() {
    const quantity = parseInt(document.getElementById('quantity').value) || 1;
    const productId = this.dataset.productId;
    const productName = this.dataset.productName;
    const productPrice = this.dataset.productPrice;
    
    addToCart(productId, productName, productPrice, quantity);
});
</script>

<?php require_once 'includes/footer.php'; ?>