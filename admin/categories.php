<?php
// admin/categories.php
require_once __DIR__ . '/../includes/header.php';

if (!isAdmin()) {
    redirect($baseUrl . '/index.php');
}

$error = '';
$success = '';

// Add category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = sanitizeInput($_POST['name']);
    if (!empty($name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
            $success = "Category added successfully.";
        } catch(PDOException $e) {
            $error = "Category already exists or error occurred.";
        }
    }
}

// Delete category
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $success = "Category deleted.";
    redirect($baseUrl . '/admin/categories.php');
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<div class="container">
    <div class="dashboard-layout">
        <aside class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="categories.php" class="active">Categories</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="orders.php">Orders</a></li>
            </ul>
        </aside>
        
        <div class="dashboard-content">
            <h2 class="mb-4">Manage Categories</h2>
            
            <?php if($error): ?>
                <div class="mb-4 text-danger" style="background: #fee2e2; padding: 1rem; border-radius: var(--radius);"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="mb-4 text-success" style="background: #d1fae5; padding: 1rem; border-radius: var(--radius);"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="card mb-4">
                <h3>Add New Category</h3>
                <form action="categories.php" method="POST" class="mt-4" style="display: flex; gap: 1rem;">
                    <input type="text" name="name" class="form-control" placeholder="Category Name" required>
                    <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                </form>
            </div>

            <div class="card">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border);">
                            <th style="padding: 1rem;">ID</th>
                            <th style="padding: 1rem;">Name</th>
                            <th style="padding: 1rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $cat): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1rem;"><?php echo $cat['id']; ?></td>
                            <td style="padding: 1rem;"><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td style="padding: 1rem;">
                                <a href="categories.php?delete=<?php echo $cat['id']; ?>" class="text-danger" onclick="return confirm('Delete this category?');"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($categories)): ?>
                        <tr><td colspan="3" style="padding: 1rem; text-align: center;">No categories found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
