<?php
require_once 'includes/admin_header.php';
require_once '../models/Product.php';
require_once '../models/ProductImage.php';
require_once '../config/database.php';

// Initialize models
$product = new Product($db);
$productImage = new ProductImage($db);

$message = '';
$error = '';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    header('Location: products.php');
    exit;
}

// Get product data
$product_data = $product->getProductById($product_id);
$product_images = $productImage->getProductImages($product_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // Handle image actions
        switch ($_POST['action']) {
            case 'delete_image':
                $image_id = (int)$_POST['image_id'];
                if ($productImage->deleteImage($image_id)) {
                    $message = "Image deleted successfully!";
                } else {
                    $error = "Failed to delete image.";
                }
                break;

            case 'set_primary':
                $image_id = (int)$_POST['image_id'];
                if ($productImage->setPrimaryImage($image_id, $product_id)) {
                    $message = "Primary image updated!";
                } else {
                    $error = "Failed to update primary image.";
                }
                break;
        }
    } else {
        // Update product data
        $update_data = [
            'product_id' => $product_id,
            'product_name' => $_POST['product_name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'stock_quantity' => $_POST['stock_quantity'],
            'category_id' => $_POST['category_id']
        ];

        $result = $product->updateProduct($update_data);

        if ($result['success']) {
            // Handle new image uploads
            if (isset($_FILES['product_images']) && $_FILES['product_images']['error'][0] !== 4) {
                $images = $_FILES['product_images'];
                $total_images = count($images['name']);
                
                for ($i = 0; $i < $total_images; $i++) {
                    if ($images['error'][$i] === 0) {
                        $image = [
                            'name' => $images['name'][$i],
                            'type' => $images['type'][$i],
                            'tmp_name' => $images['tmp_name'][$i],
                            'error' => $images['error'][$i],
                            'size' => $images['size'][$i]
                        ];
                        
                        // If no primary image exists, make the first new image primary
                        $is_primary = empty($product_images) && $i === 0;
                        
                        $productImage->addImage($product_id, $image, $is_primary);
                    }
                }
            }
            
            $message = "Product updated successfully!";
            // Refresh product images
            $product_images = $productImage->getProductImages($product_id);
        } else {
            $error = "Error updating product: " . $result['error'];
        }
    }
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Product</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" 
                           value="<?php echo htmlspecialchars($product_data['product_name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>
                        <?php echo htmlspecialchars($product_data['description']); ?>
                    </textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" 
                                   value="<?php echo $product_data['price']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                   value="<?php echo $product_data['stock_quantity']; ?>" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php
                        $categories = $product->getAllCategories();
                        foreach ($categories as $category) {
                            $selected = ($category['category_id'] == $product_data['category_id']) ? 'selected' : '';
                            echo "<option value=\"{$category['category_id']}\" {$selected}>{$category['category_name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Current Images -->
                <div class="mb-3">
                    <label class="form-label">Current Images</label>
                    <div class="d-flex flex-wrap gap-3">
                        <?php foreach ($product_images as $image): ?>
                            <div class="position-relative">
                                <img src="../uploads/products/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                     alt="Product Image" 
                                     class="img-thumbnail" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 p-2">
                                    <?php if (!$image['is_primary']): ?>
                                        <form action="" method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="set_primary">
                                            <input type="hidden" name="image_id" value="<?php echo $image['image_id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-success" title="Set as primary">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="" method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="delete_image">
                                        <input type="hidden" name="image_id" value="<?php echo $image['image_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete image">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                <?php if ($image['is_primary']): ?>
                                    <div class="position-absolute bottom-0 start-0 p-2">
                                        <span class="badge bg-primary">Primary</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Add New Images -->
                <div class="mb-3">
                    <label for="product_images" class="form-label">Add New Images</label>
                    <input type="file" class="form-control" id="product_images" name="product_images[]" multiple accept="image/*">
                    <div id="image_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
    </div>
</div>

<script>
// Image preview functionality
document.getElementById('product_images').addEventListener('change', function(e) {
    const preview = document.getElementById('image_preview');
    preview.innerHTML = ''; // Clear existing previews
    
    for (const file of this.files) {
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'preview-image';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                `;
                preview.appendChild(div);
            }
            reader.readAsDataURL(file);
        }
    }
});
</script>

<?php require_once 'includes/admin_footer.php'; ?>
