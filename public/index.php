<?php
// public/index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ERP System Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Your custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">ERP System</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../src/customer/list.php">Customers</a></li>
                    <li class="nav-item"><a class="nav-link" href="../src/item/list.php">Items</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Reports</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../src/report/invoice.php">Invoice Report</a></li>
                            <li><a class="dropdown-item" href="../src/report/invoice_item.php">Invoice Item Report</a></li>
                            <li><a class="dropdown-item" href="../src/report/item.php">Item Report</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="p-5 mb-4 bg-light rounded-3">
            <h1 class="display-5 fw-bold">Welcome to the ERP System</h1>
            <p class="col-md-8 fs-4">
                Use the navigation bar to manage customers, items, and view reports.<br>
                This system is built with PHP, MySQL, Bootstrap, and JavaScript.
            </p>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Customer Management</h5>
                        <p class="card-text">Add, edit, or delete customers and view all customer details.</p>
                        <a href="../src/customer/list.php" class="btn btn-primary">Go to Customers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Item Management</h5>
                        <p class="card-text">Manage items, categories, and subcategories.</p>
                        <a href="../src/item/list.php" class="btn btn-primary">Go to Items</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Reports</h5>
                        <p class="card-text">View invoice, invoice item, and item reports.</p>
                        <a href="../src/report/invoice.php" class="btn btn-primary">View Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (for dropdowns, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
