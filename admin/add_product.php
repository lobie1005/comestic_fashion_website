<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session_validation.php';
require_once __DIR__ . '/../models/Product.php';

// Initialize database connection
$database = new Database();
$db = $database->connect();

// Ensure the user is an admin
require_admin();

// Initialize Product model
$product = new Product($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle image upload
        $image_url = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../uploads/products/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($file_extension, $allowed_extensions)) {
                throw new Exception('Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.');
            }

            $file_name = uniqid('product_') . '.' . $file_extension;
            $upload_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_url = '/uploads/products/' . $file_name;
            } else {
                throw new Exception('Failed to upload image.');
            }
        }

        // Create product data array
        $product_data = [
            'product_name' => $_POST['product_name'],
            'description' => $_POST['description'],
            'price' => floatval($_POST['price']),
            'stock_quantity' => intval($_POST['stock_quantity']),
            'category_id' => intval($_POST['category_id']),
            'image_url' => $image_url
        ];

        // Create product
        if ($product->createProduct($product_data)) {
            $_SESSION['success_message'] = "Product added successfully!";
            header('Location: manage_products.php');
            exit;
        } else {
            throw new Exception('Failed to create product.');
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Product</h1>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="manage_products.php">Products</a></li>
            <li class="breadcrumb-item active">Add Product</li>
        </ol>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['error_message'];
            unset($_SESSION['error_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Product Details
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" 
                                   value="<?php echo isset($_POST['product_name']) ? htmlspecialchars($_POST['product_name']) : ''; ?>" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php 
                                echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; 
                            ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price ($)</label>
                                    <input type="number" class="form-control" id="price" name="price" 
                                           step="0.01" min="0" 
                                           value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                           min="0" 
                                           value="<?php echo isset($_POST['stock_quantity']) ? htmlspecialchars($_POST['stock_quantity']) : ''; ?>" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php
                                $categories = [
                                    ['category_id' => 1, 'category_name' => 'Electronics'],
                                    ['category_id' => 2, 'category_name' => 'Clothing'],
                                    ['category_id' => 3, 'category_name' => 'Books'],
                                    ['category_id' => 4, 'category_name' => 'Home & Garden'],
                                    ['category_id' => 5, 'category_name' => 'Sports & Outdoors']
                                ];
                                foreach ($categories as $category) {
                                    $selected = isset($_POST['category_id']) && $_POST['category_id'] == $category['category_id'] ? 'selected' : '';
                                    echo "<option value=\"{$category['category_id']}\" {$selected}>{$category['category_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div id="image_preview" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Product
                    </button>
                    <a href="manage_products.php" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#image_preview img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('image_preview');
    preview.innerHTML = '';
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        }
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
