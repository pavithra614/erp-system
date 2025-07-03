<?php
require '../../config/db.php';
$sql = "SELECT DISTINCT i.item_name, c.category, s.sub_category, i.quantity 
        FROM item i 
        JOIN item_category c ON i.item_category = c.id 
        JOIN item_subcategory s ON i.item_subcategory = s.id
        ORDER BY c.category, s.sub_category, i.item_name";
$items = $pdo->query($sql)->fetchAll();

// Calculate statistics
$total_items = count($items);
$total_quantity = array_sum(array_column($items, 'quantity'));
$low_stock_items = array_filter($items, function($item) { return $item['quantity'] < 10; });
$categories = array_unique(array_column($items, 'category'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Report</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }
        .stats-card {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            color: white;
            border-radius: 0.5rem;
        }
        .stats-card-success {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            color: white;
            border-radius: 0.5rem;
        }
        .stats-card-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            color: #212529;
            border-radius: 0.5rem;
        }
        .stats-card-info {
            background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
            color: white;
            border-radius: 0.5rem;
        }
        .category-badge {
            background-color: #e3f2fd;
            color: #1565c0;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }
        .subcategory-badge {
            background-color: #f3e5f5;
            color: #7b1fa2;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 500;
        }
        .quantity-low {
            background-color: #ffebee;
            color: #c62828;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }
        .quantity-normal {
            background-color: #e8f5e8;
            color: #2e7d32;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-box-seam-fill fs-3 text-primary me-2"></i>
                <h2 class="mb-0">Item Report</h2>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>Print Report
                </button>
                <button class="btn btn-primary" onclick="exportToCSV()">
                    <i class="bi bi-download me-1"></i>Export CSV
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Total Items</h6>
                            <h3 class="mb-0"><?= number_format($total_items) ?></h3>
                        </div>
                        <i class="bi bi-box-seam fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card-success p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Total Quantity</h6>
                            <h3 class="mb-0"><?= number_format($total_quantity) ?></h3>
                        </div>
                        <i class="bi bi-stack fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card-warning p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Low Stock Items</h6>
                            <h3 class="mb-0"><?= count($low_stock_items) ?></h3>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card-info p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Categories</h6>
                            <h3 class="mb-0"><?= count($categories) ?></h3>
                        </div>
                        <i class="bi bi-collection fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Table Card -->
        <div class="card">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-table me-2"></i>
                        <span class="fw-semibold">Item Inventory Details</span>
                    </div>
                    <span class="badge bg-light text-primary"><?= count($items) ?> Items</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="itemTable">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Stock Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No items found in inventory
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-box text-primary me-2"></i>
                                                <span class="fw-semibold">
                                                    <?= htmlspecialchars($item['item_name']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="category-badge">
                                                <?= htmlspecialchars($item['category']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="subcategory-badge">
                                                <?= htmlspecialchars($item['sub_category']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold <?= $item['quantity'] < 10 ? 'text-danger' : 'text-success' ?>">
                                                <?= number_format($item['quantity']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($item['quantity'] < 10): ?>
                                                <span class="quantity-low">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>Low Stock
                                                </span>
                                            <?php elseif ($item['quantity'] < 50): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-dash-circle me-1"></i>Medium Stock
                                                </span>
                                            <?php else: ?>
                                                <span class="quantity-normal">
                                                    <i class="bi bi-check-circle me-1"></i>In Stock
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <?php if (!empty($low_stock_items)): ?>
            <div class="alert alert-warning mt-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Low Stock Alert:</strong> <?= count($low_stock_items) ?> item(s) are running low on stock (less than 10 units).
                        Please consider restocking these items.
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Export to CSV Function -->
    <script>
        function exportToCSV() {
            const table = document.getElementById('itemTable');
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            // Add header
            csv.push(['Item Name', 'Category', 'Subcategory', 'Quantity', 'Stock Status']);
            
            // Add data rows (skip header)
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cols = row.querySelectorAll('td');
                if (cols.length > 0) {
                    csv.push([
                        cols[0].textContent.trim(),
                        cols[1].textContent.trim(),
                        cols[2].textContent.trim(),
                        cols[3].textContent.trim(),
                        cols[4].textContent.trim()
                    ]);
                }
            }
            
            // Convert to CSV string
            const csvContent = csv.map(row => row.join(',')).join('\n');
            
            // Download
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'item_report_' + new Date().toISOString().split('T')[0] + '.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>
