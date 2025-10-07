<?php 
require 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Get statistics
$totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch(PDO::FETCH_ASSOC)['count'];
$totalStock = $conn->query("SELECT SUM(stock) as total FROM products")->fetch(PDO::FETCH_ASSOC)['total'];
$totalValue = $conn->query("SELECT SUM(price * stock) as value FROM products")->fetch(PDO::FETCH_ASSOC)['value'];

$stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #4361ee, #3f37c9);
        }
        .sidebar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 1rem;
        }
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .welcome-banner {
            background: linear-gradient(135deg, #4361ee, #3f37c9);
            color: white;
            border-radius: 12px;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="products.php">
                <i class="fas fa-boxes me-2"></i>ProductManager
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i>
                    <?php echo htmlspecialchars($_SESSION['user']['email']); ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <?php if ($_SESSION['user']['is_admin']): ?>
            <div class="col-lg-3 mb-4">
                <div class="sidebar">
                    <h6 class="text-muted mb-3"><i class="fas fa-tachometer-alt me-2"></i>Navigation</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="products.php">
                                <i class="fas fa-list me-2"></i>All Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add_product.php">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
            <?php else: ?>
            <div class="col-12">
            <?php endif; ?>

                <div class="welcome-banner mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2><i class="fas fa-boxes me-2"></i>Product Management</h2>
                            <p class="mb-0">Manage your product inventory efficiently</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group">
                                <a href="products.php" class="btn btn-light">
                                    <i class="fas fa-sync-alt me-1"></i>Refresh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stats-card">
                            <i class="fas fa-cubes text-primary fa-2x mb-3"></i>
                            <h4 class="text-primary"><?php echo $totalProducts; ?></h4>
                            <p class="text-muted mb-0">Total Products</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <i class="fas fa-boxes text-success fa-2x mb-3"></i>
                            <h4 class="text-success"><?php echo $totalStock ?: 0; ?></h4>
                            <p class="text-muted mb-0">Total Stock</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <i class="fas fa-dollar-sign text-info fa-2x mb-3"></i>
                            <h4 class="text-info">$<?php echo number_format($totalValue ?: 0, 2); ?></h4>
                            <p class="text-muted mb-0">Total Value</p>
                        </div>
                    </div>
                </div>

                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0"><i class="fas fa-list me-2"></i>Product List</h4>
                        <?php if ($_SESSION['user']['is_admin']): ?>
                        <a href="add_product.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Product
                        </a>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($products)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No products found</h5>
                        <?php if ($_SESSION['user']['is_admin']): ?>
                        <p class="text-muted">Get started by adding your first product</p>
                        <a href="add_product.php" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-2"></i>Add Product
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Last Updated</th>
                                    <?php if ($_SESSION['user']['is_admin']): ?>
                                    <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $p): ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo $p['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($p['name']); ?></strong>
                                    </td>
                                    <td class="text-muted">
                                        <?php echo strlen($p['description']) > 50 ? substr(htmlspecialchars($p['description']), 0, 50) . '...' : htmlspecialchars($p['description']); ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success fs-6">$<?php echo number_format($p['price'], 2); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $p['stock'] > 10 ? 'bg-primary' : 'bg-warning'; ?> fs-6">
                                            <?php echo $p['stock']; ?> units
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <?php echo date('M j, Y g:i A', strtotime($p['updated_at'])); ?>
                                    </td>
                                    <?php if ($_SESSION['user']['is_admin']): ?>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_product.php?id=<?php echo $p['id']; ?>" 
                                               class="btn btn-outline-danger" 
                                               onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($p['name']); ?>?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>