<?php
require '../../config/db.php';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$where = '';
if ($from && $to) {
    $where = "WHERE i.date BETWEEN '$from' AND '$to'";
}
$sql = "SELECT i.invoice_no, i.date, c.first_name, d.district, i.item_count, i.amount 
        FROM invoice i 
        JOIN customer c ON i.customer = c.id 
        JOIN district d ON c.district = d.id $where
        ORDER BY i.date DESC";
$invoices = $pdo->query($sql)->fetchAll();

// Calculate totals
$total_amount = array_sum(array_column($invoices, 'amount'));
$total_invoices = count($invoices);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Report</title>
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
        .invoice-badge {
            background-color: #e3f2fd;
            color: #1565c0;
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
                <i class="bi bi-receipt fs-3 text-primary me-2"></i>
                <h2 class="mb-0">Invoice Report</h2>
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
            <div class="col-md-6">
                <div class="stats-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Total Invoices</h6>
                            <h3 class="mb-0"><?= number_format($total_invoices) ?></h3>
                        </div>
                        <i class="bi bi-receipt-cutoff fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Total Revenue</h6>
                            <h3 class="mb-0">LKR <?= number_format($total_amount, 2) ?></h3>
                        </div>
                        <i class="bi bi-currency-exchange fs-1 opacity-50"></i>
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
                    <a href="invoice.php" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Invoice Table Card -->
        <div class="card">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-table me-2"></i>
                        <span class="fw-semibold">Invoice Details</span>
                        <?php if ($from && $to): ?>
                            <span class="badge bg-light text-primary ms-2">
                                <?= date('M d, Y', strtotime($from)) ?> - <?= date('M d, Y', strtotime($to)) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <span class="badge bg-light text-primary"><?= count($invoices) ?> Records</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>District</th>
                                <th class="text-center">Item Count</th>
                                <th class="text-end">Amount (LKR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($invoices)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No invoices found for the selected date range
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($invoices as $inv): ?>
                                    <tr>
                                        <td>
                                            <span class="invoice-badge">
                                                <?= htmlspecialchars($inv['invoice_no']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-primary">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                <?= date('M d, Y', strtotime($inv['date'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">
                                                <?= htmlspecialchars($inv['first_name']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?= htmlspecialchars($inv['district']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">
                                                <?= htmlspecialchars($inv['item_count']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold text-success">
                                                LKR <?= number_format($inv['amount'], 2) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <?php if (!empty($invoices)): ?>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="5" class="text-end">Total Amount:</th>
                                    <th class="text-end text-primary">
                                        LKR <?= number_format($total_amount, 2) ?>
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
            const table = document.querySelector('table');
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            // Add header
            csv.push(['Invoice No', 'Date', 'Customer', 'District', 'Item Count', 'Amount (LKR)']);
            
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
                        cols[5].textContent.trim()
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
            a.download = 'invoice_report_' + new Date().toISOString().split('T')[0] + '.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>
