<?php
// auth/login.php
require_once __DIR__ . '/../includes/header.php';

if (isLoggedIn()) {
    redirect($baseUrl . '/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] === 'admin') {
                redirect($baseUrl . '/admin/index.php');
            } else {
                redirect($baseUrl . '/index.php');
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<div class="container">
    <div class="card" style="max-width: 500px; margin: 2rem auto;">
        <h2 class="text-center mb-4">Welcome Back</h2>
        
        <?php if($error): ?>
            <div class="mb-4 text-danger" style="background: #fee2e2; padding: 1rem; border-radius: var(--radius);">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        
        <p class="text-center mt-4 text-muted">
            Don't have an account? <a href="register.php" style="color: var(--primary);">Sign up here</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
