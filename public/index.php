<?php
// public/index.php
require '../config/db.php';

// Fetch dashboard statistics
try {
    $total_customers = $pdo->query("SELECT COUNT(*) FROM customer")->fetchColumn();
    $total_items = $pdo->query("SELECT COUNT(*) FROM item")->fetchColumn();
    $total_invoices = $pdo->query("SELECT COUNT(*) FROM invoice")->fetchColumn();
    $total_revenue = $pdo->query("SELECT SUM(amount) FROM invoice")->fetchColumn() ?? 0;
    $low_stock_items = $pdo->query("SELECT COUNT(*) FROM item WHERE quantity < 10")->fetchColumn();
    
    // Recent activities
    $recent_customers = $pdo->query("SELECT first_name, last_name FROM customer ORDER BY id DESC LIMIT 5")->fetchAll();
    $recent_invoices = $pdo->query("SELECT invoice_no, amount, date FROM invoice ORDER BY date DESC LIMIT 5")->fetchAll();
} catch (Exception $e) {
    // Handle database errors gracefully
    $total_customers = $total_items = $total_invoices = $total_revenue = $low_stock_items = 0;
    $recent_customers = $recent_invoices = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ERP System Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
   
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="bi bi-building me-2"></i>
                ERP System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../src/customer/list.php">
                            <i class="bi bi-people me-1"></i>Customers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../src/item/list.php">
                            <i class="bi bi-box-seam me-1"></i>Items
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-graph-up me-1"></i>Reports
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../src/report/invoice.php">
                                <i class="bi bi-receipt me-2"></i>Invoice Report
                            </a></li>
                            <li><a class="dropdown-item" href="../src/report/invoice_item.php">
                                <i class="bi bi-receipt-cutoff me-2"></i>Invoice Item Report
                            </a></li>
                            <li><a class="dropdown-item" href="../src/report/item.php">
                                <i class="bi bi-box-seam me-2"></i>Item Report
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <!-- Hero Section -->
        <div class="hero-section text-center">
            <h1 class="display-4 fw-bold mb-3">
                <i class="bi bi-graph-up-arrow me-3"></i>
                Welcome to Your ERP Dashboard
            </h1>
            <p class="lead mb-4">
                Streamline your business operations with our comprehensive Enterprise Resource Planning system.
                Manage customers, inventory, and generate insightful reports all in one place.
            </p>
            <div class="row text-center">
                <div class="col-md-4">
                    <i class="bi bi-shield-check display-6 mb-2"></i>
                    <h5>Secure & Reliable</h5>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-lightning display-6 mb-2"></i>
                    <h5>Fast Performance</h5>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-graph-up display-6 mb-2"></i>
                    <h5>Business Growth</h5>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card stats-card-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Total Customers</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($total_customers) ?></h2>
                        </div>
                        <i class="bi bi-people-fill display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card stats-card-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Total Items</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($total_items) ?></h2>
                        </div>
                        <i class="bi bi-box-seam-fill display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card stats-card-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Total Invoices</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($total_invoices) ?></h2>
                        </div>
                        <i class="bi bi-receipt-cutoff display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card stats-card-danger">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Low Stock Items</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($low_stock_items) ?></h2>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 opacity-75">Total Revenue</h4>
                            <h1 class="mb-0 fw-bold">LKR <?= number_format($total_revenue, 2) ?></h1>
                        </div>
                        <i class="bi bi-currency-exchange" style="font-size: 4rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Feature Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="dashboard-card card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill feature-icon"></i>
                        <h5 class="card-title fw-bold">Customer Management</h5>
                        <p class="card-text text-muted">
                            Add, edit, and manage customer information. Track customer details, 
                            contact information, and location data efficiently.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="../src/customer/list.php" class="btn btn-primary">
                                <i class="bi bi-arrow-right me-1"></i>Manage Customers
                            </a>
                            <a href="../src/customer/add.php" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-1"></i>Add New Customer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-box-seam-fill feature-icon"></i>
                        <h5 class="card-title fw-bold">Inventory Management</h5>
                        <p class="card-text text-muted">
                            Manage your product inventory, categories, and stock levels. 
                            Track item quantities and monitor low stock alerts.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="../src/item/list.php" class="btn btn-success">
                                <i class="bi bi-arrow-right me-1"></i>Manage Items
                            </a>
                            <a href="../src/item/add.php" class="btn btn-outline-success">
                                <i class="bi bi-plus-circle me-1"></i>Add New Item
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-graph-up-arrow feature-icon"></i>
                        <h5 class="card-title fw-bold">Business Reports</h5>
                        <p class="card-text text-muted">
                            Generate comprehensive reports for invoices, items, and sales analysis. 
                            Export data and gain valuable business insights.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="../src/report/invoice.php" class="btn btn-warning">
                                <i class="bi bi-arrow-right me-1"></i>View Reports
                            </a>
                            <a href="../src/report/item.php" class="btn btn-outline-warning">
                                <i class="bi bi-file-earmark-text me-1"></i>Item Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="recent-activity">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        Recent Customers
                    </h5>
                    <?php if (!empty($recent_customers)): ?>
                        <?php foreach ($recent_customers as $customer): ?>
                            <div class="activity-item">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-plus text-success me-3"></i>
                                    <div>
                                        <strong><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></strong>
                                        <small class="text-muted d-block">New customer added</small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No recent customers found.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="recent-activity">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-receipt text-primary me-2"></i>
                        Recent Invoices
                    </h5>
                    <?php if (!empty($recent_invoices)): ?>
                        <?php foreach ($recent_invoices as $invoice): ?>
                            <div class="activity-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-receipt-cutoff text-warning me-3"></i>
                                        <div>
                                            <strong><?= htmlspecialchars($invoice['invoice_no']) ?></strong>
                                            <small class="text-muted d-block"><?= date('M d, Y', strtotime($invoice['date'])) ?></small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success">LKR <?= number_format($invoice['amount'], 2) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No recent invoices found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Dashboard JavaScript -->
    <script>
        // Add smooth scrolling and interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Animate statistics cards on load
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Add click tracking for analytics
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    console.log('Button clicked:', this.textContent.trim());
                });
            });
        });
    </script>
</body>
</html>
