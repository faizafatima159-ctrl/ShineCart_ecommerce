<?php
// auth/register.php
require_once __DIR__ . '/../includes/header.php';

if (isLoggedIn()) {
    redirect($baseUrl . '/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email is already registered.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashed_password])) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Something went wrong. Please try again later.";
            }
        }
    }
}
?>

<div class="container">
    <div class="card" style="max-width: 500px; margin: 2rem auto;">
        <h2 class="text-center mb-4">Create an Account</h2>
        
        <?php if($error): ?>
            <div class="mb-4 text-danger" style="background: #fee2e2; padding: 1rem; border-radius: var(--radius);">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="mb-4 text-success" style="background: #d1fae5; padding: 1rem; border-radius: var(--radius);">
                <?php echo $success; ?>
                <div class="mt-2 text-center">
                    <a href="<?php echo $baseUrl; ?>/auth/login.php" class="btn btn-primary">Go to Login</a>
                </div>
            </div>
        <?php else: ?>
        
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="6">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign Up</button>
            </form>
            
            <p class="text-center mt-4 text-muted">
                Already have an account? <a href="login.php" style="color: var(--primary);">Login here</a>
            </p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
