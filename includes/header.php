<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ERP System'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }
        .alert {
            border-radius: 0.375rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/erp-system/public/">
                <i class="fas fa-building me-2"></i>ERP System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="customerDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-users me-1"></i>Customers
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/erp-system/src/customer/add.php">
                                <i class="fas fa-plus me-2"></i>Add Customer</a></li>
                            <li><a class="dropdown-item" href="/erp-system/src/customer/list.php">
                                <i class="fas fa-list me-2"></i>View Customers</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="itemDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-box me-1"></i>Items
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/erp-system/src/item/add.php">
                                <i class="fas fa-plus me-2"></i>Add Item</a></li>
                            <li><a class="dropdown-item" href="/erp-system/src/item/list.php">
                                <i class="fas fa-list me-2"></i>View Items</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-chart-bar me-1"></i>Reports
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/erp-system/src/report/invoice.php">
                                <i class="fas fa-file-invoice me-2"></i>Invoice Report</a></li>
                            <li><a class="dropdown-item" href="/erp-system/src/report/invoice_item.php">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Invoice Item Report</a></li>
                            <li><a class="dropdown-item" href="/erp-system/src/report/item.php">
                                <i class="fas fa-boxes me-2"></i>Item Report</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">