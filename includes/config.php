<?php
session_start();

$host = 'localhost';
$dbname = 'product_management';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If database doesn't exist, show setup instructions
    if ($e->getCode() == 1049) { // Unknown database
        die("
        <!DOCTYPE html>
        <html>
        <head>
            <title>Database Setup Required</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body class='bg-light'>
            <div class='container mt-5'>
                <div class='row justify-content-center'>
                    <div class='col-md-6'>
                        <div class='card shadow'>
                            <div class='card-body text-center p-5'>
                                <h2>🚀 Setup Required</h2>
                                <p class='text-muted'>Database needs to be initialized before using the application.</p>
                                <div class='mt-4'>
                                    <a href='db/setup.php' class='btn btn-primary btn-lg'>Initialize Database</a>
                                </div>
                                <p class='mt-3 text-muted'><small>This will create the database and necessary tables</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ");
    } else {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>