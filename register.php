<?php 
require 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    // Check if this is the first user (make them admin)
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $is_admin = ($result['count'] == 0) ? 1 : 0;

    try {
        $stmt = $conn->prepare("INSERT INTO users (email, password, is_admin) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $is_admin]);
        
        echo '<div class="alert alert-success">Registration successful! <a href="login.php">Login here</a></div>';
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate email
            echo '<div class="alert alert-danger">Email already exists.</div>';
        } else {
            echo '<div class="alert alert-danger">Registration failed: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <i class="fas fa-user-plus fa-2x text-primary mb-3"></i>
            <h3 class="fw-bold">Create Account</h3>
            <p class="text-muted">Join us today</p>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" class="form-control" placeholder="Create a password" required minlength="6">
                </div>
                <div class="form-text">Password must be at least 6 characters long.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>

            <div class="text-center">
                <p class="text-muted mb-0">Already have an account? 
                    <a href="login.php" class="text-primary text-decoration-none">Sign in here</a>
                </p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>