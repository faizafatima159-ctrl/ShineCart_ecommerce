<?php
// admin/products.php
require_once __DIR__ . '/../includes/header.php';

if (!isAdmin()) {
    redirect($baseUrl . '/index.php');
}

$error = '';
$success = '';

// Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = sanitizeInput($_POST['name']);
    $price = (float)$_POST['price'];
    $description = sanitizeInput($_POST['description']);
    $category_id = (int)$_POST['category_id'];
    
    // Image Upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $newFilename = uniqid() . '.' . $ext;
            $uploadDir = __DIR__ . '/../assets/images/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFilename)) {
                $image = $newFilename;
            }
        }
    }

    if (!empty($name) && $price > 0) {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image, category_id) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $price, $description, $image, $category_id])) {
            $success = "Product added successfully.";
        } else {
            $error = "Error adding product.";
        }
    } else {
        $error = "Name and valid price are required.";
    }
}

// Delete product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    redirect($baseUrl . '/admin/products.php');
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
?>

<div class="container">
    <div class="dashboard-layout">
        <aside class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="products.php" class="active">Products</a></li>
                <li><a href="orders.php">Orders</a></li>
            </ul>
        </aside>
        
        <div class="dashboard-content">
            <h2 class="mb-4">Manage Products</h2>
            
            <?php if($error): ?>
                <div class="mb-4 text-danger" style="background: #fee2e2; padding: 1rem; border-radius: var(--radius);"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="mb-4 text-success" style="background: #d1fae5; padding: 1rem; border-radius: var(--radius);"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="card mb-4">
                <h3>Add New Product</h3>
                <form action="products.php" method="POST" enctype="multipart/form-data" class="mt-4">
                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                    </div>
                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                </form>
            </div>

            <div class="card">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border);">
                            <th style="padding: 1rem;">ID</th>
                            <th style="padding: 1rem;">Image</th>
                            <th style="padding: 1rem;">Name</th>
                            <th style="padding: 1rem;">Price</th>
                            <th style="padding: 1rem;">Category</th>
                            <th style="padding: 1rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $p): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1rem;"><?php echo $p['id']; ?></td>
                            <td style="padding: 1rem;">
                                <?php if($p['image']): ?>
                                    <img src="<?php echo $baseUrl; ?>/assets/images/uploads/<?php echo htmlspecialchars($p['image']); ?>" width="50" style="border-radius: 4px;">
                                <?php else: ?>
                                    <div style="width:50px;height:50px;background:#eee;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;">No Img</div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem;"><?php echo htmlspecialchars($p['name']); ?></td>
                            <td style="padding: 1rem;">$<?php echo number_format($p['price'], 2); ?></td>
                            <td style="padding: 1rem;"><?php echo htmlspecialchars($p['category_name'] ?? 'Uncategorized'); ?></td>
                            <td style="padding: 1rem;">
                                <a href="products.php?delete=<?php echo $p['id']; ?>" class="text-danger" onclick="return confirm('Delete this product?');"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($products)): ?>
                        <tr><td colspan="6" style="padding: 1rem; text-align: center;">No products found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
