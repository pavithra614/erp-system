<?php
require '../../config/db.php';

// Handle search and filter
$search = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';

$sql = "SELECT i.*, c.category, s.sub_category FROM item i 
        JOIN item_category c ON i.item_category = c.id 
        JOIN item_subcategory s ON i.item_subcategory = s.id WHERE 1=1";

if ($search) {
    $sql .= " AND (i.item_name LIKE '%$search%' OR i.item_code LIKE '%$search%')";
}

if ($category_filter) {
    $sql .= " AND i.item_category = '$category_filter'";
}

$items = $pdo->query($sql)->fetchAll();
$categories = $pdo->query("SELECT * FROM item_category")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item List</title>
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
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .search-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-box-seam-fill fs-3 text-primary me-2"></i>
                <h2 class="mb-0">Item List</h2>
            </div>
            <a href="add.php" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Add New Item
            </a>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search by name or item code" 
                               value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter by Category</label>
                    <select class="form-select" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-funnel me-1"></i>Search
                    </button>
                    <a href="list.php" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Items Table Card -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-list-ul me-2"></i>
                        <span class="fw-semibold">Items</span>
                        <span class="badge bg-primary ms-2"><?= count($items) ?></span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No items found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($item['item_code']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?= htmlspecialchars($item['category']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?= htmlspecialchars($item['sub_category']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold <?= $item['quantity'] < 10 ? 'text-danger' : 'text-success' ?>">
                                                <?= htmlspecialchars($item['quantity']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-primary fw-semibold">
                                                $<?= number_format($item['unit_price'], 2) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="edit.php?id=<?= $item['id'] ?>" 
                                                   class="btn btn-outline-primary" 
                                                   title="Edit Item">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="delete.php?id=<?= $item['id'] ?>" 
                                                   class="btn btn-outline-danger" 
                                                   title="Delete Item"
                                                   onclick="return confirm('Are you sure you want to delete this item?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
