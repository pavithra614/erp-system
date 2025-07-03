<?php
require '../../config/db.php';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$where = '';
if ($from && $to) {
    $where = "WHERE i.date BETWEEN '$from' AND '$to'";
}
$sql = "SELECT im.invoice_no, i.date, c.first_name, itm.item_name, itm.item_code, 
        ic.category, im.unit_price 
        FROM invoice_master im 
        JOIN invoice i ON im.invoice_no = i.invoice_no 
        JOIN customer c ON i.customer = c.id 
        JOIN item itm ON im.item_id = itm.id 
        JOIN item_category ic ON itm.item_category = ic.id $where
        ORDER BY i.date DESC, im.invoice_no";
$items = $pdo->query($sql)->fetchAll();

// Calculate statistics
$total_items = count($items);
$total_value = array_sum(array_column($items, 'unit_price'));
$unique_invoices = count(array_unique(array_column($items, 'invoice_no')));
$categories = array_unique(array_column($items, 'category'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Item Report</title>
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
        .filter-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
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
        .invoice-badge {
            background-color: #e3f2fd;
            color: #1565c0;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }
        .item-code-badge {
            background-color: #f3e5f5;
            color: #7b1fa2;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }
        .category-badge {
            background-color: #e8f5e8;
            color: #2e7d32;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-receipt-cutoff fs-3 text-primary me-2"></i>
                <h2 class="mb-0">Invoice Item Report</h2>
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
                            <h6 class="mb-0 opacity-75">Total Value</h6>
                            <h3 class="mb-0">LKR <?= number_format($total_value, 2) ?></h3>
                        </div>
                        <i class="bi bi-currency-exchange fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card-warning p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Unique Invoices</h6>
                            <h3 class="mb-0"><?= number_format($unique_invoices) ?></h3>
                        </div>
                        <i class="bi bi-receipt fs-1 opacity-50"></i>
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

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">From Date</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-calendar-event"></i>
                        </span>
                        <input type="date" class="form-control" name="from" 
                               value="<?= htmlspecialchars($from) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">To Date</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-calendar-check"></i>
                        </span>
                        <input type="date" class="form-control" name="to" 
                               value="<?= htmlspecialchars($to) ?>">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="invoice_item.php" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Invoice Items Table Card -->
        <div class="card">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-table me-2"></i>
                        <span class="fw-semibold">Invoice Item Details</span>
                        <?php if ($from && $to): ?>
                            <span class="badge bg-light text-primary ms-2">
                                <?= date('M d, Y', strtotime($from)) ?> - <?= date('M d, Y', strtotime($to)) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <span class="badge bg-light text-primary"><?= count($items) ?> Records</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="invoiceItemTable">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Item Name</th>
                                <th>Item Code</th>
                                <th>Category</th>
                                <th class="text-end">Unit Price (LKR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No invoice items found for the selected date range
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $row): ?>
                                    <tr>
                                        <td>
                                            <span class="invoice-badge">
                                                <?= htmlspecialchars($row['invoice_no']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-primary">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                <?= date('M d, Y', strtotime($row['date'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person text-secondary me-2"></i>
                                                <span class="fw-semibold">
                                                    <?= htmlspecialchars($row['first_name']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-box text-primary me-2"></i>
                                                <span class="fw-semibold">
                                                    <?= htmlspecialchars($row['item_name']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="item-code-badge">
                                                <?= htmlspecialchars($row['item_code']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="category-badge">
                                                <?= htmlspecialchars($row['category']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold text-success">
                                                LKR <?= number_format($row['unit_price'], 2) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <?php if (!empty($items)): ?>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="6" class="text-end">Total Value:</th>
                                    <th class="text-end text-primary">
                                        LKR <?= number_format($total_value, 2) ?>
                                    </th>
                                </tr>
                            </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Export to CSV Function -->
    <script>
        function exportToCSV() {
            const table = document.getElementById('invoiceItemTable');
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            // Add header
            csv.push(['Invoice No', 'Date', 'Customer', 'Item Name', 'Item Code', 'Category', 'Unit Price (LKR)']);
            
            // Add data rows (skip header and footer)
            for (let i = 1; i < rows.length - 1; i++) {
                const row = rows[i];
                const cols = row.querySelectorAll('td');
                if (cols.length > 0) {
                    csv.push([
                        cols[0].textContent.trim(),
                        cols[1].textContent.trim(),
                        cols[2].textContent.trim(),
                        cols[3].textContent.trim(),
                        cols[4].textContent.trim(),
                        cols[5].textContent.trim(),
                        cols[6].textContent.trim()
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
            a.download = 'invoice_item_report_' + new Date().toISOString().split('T')[0] + '.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>
