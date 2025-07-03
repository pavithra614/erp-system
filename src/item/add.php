<?php
require '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_code = $_POST['item_code'];
    $item_name = $_POST['item_name'];
    $item_category = $_POST['item_category'];
    $item_subcategory = $_POST['item_subcategory'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];

    if ($item_code && $item_name && $item_category && $item_subcategory && $quantity && $unit_price) {
        $stmt = $pdo->prepare("INSERT INTO item (item_code, item_name, item_category, item_subcategory, quantity, unit_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$item_code, $item_name, $item_category, $item_subcategory, $quantity, $unit_price]);
        header('Location: list.php');
        exit;
    } else {
        $error = "All fields are required.";
    }
}

// Fetch categories and subcategories
$categories = $pdo->query("SELECT * FROM item_category")->fetchAll();
$subcats = $pdo->query("SELECT * FROM item_subcategory")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .required::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="form-container">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill fs-3 text-primary me-2"></i>
                    <h2 class="mb-0">Add New Item</h2>
                </div>
                <a href="list.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>

            <!-- Form Card -->
            <div class="card">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-box-seam me-2"></i>
                        <span class="fw-semibold">Item Information</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?= htmlspecialchars($error) ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <!-- Item Code -->
                            <div class="col-md-6">
                                <label for="item_code" class="form-label required">Item Code</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-upc-scan"></i>
                                    </span>
                                    <input type="text" class="form-control" id="item_code" name="item_code" 
                                           placeholder="Enter item code" required 
                                           value="<?= isset($_POST['item_code']) ? htmlspecialchars($_POST['item_code']) : '' ?>">
                                    <div class="invalid-feedback">
                                        Please provide a valid item code.
                                    </div>
                                </div>
                            </div>

                            <!-- Item Name -->
                            <div class="col-md-6">
                                <label for="item_name" class="form-label required">Item Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-tag"></i>
                                    </span>
                                    <input type="text" class="form-control" id="item_name" name="item_name" 
                                           placeholder="Enter item name" required 
                                           value="<?= isset($_POST['item_name']) ? htmlspecialchars($_POST['item_name']) : '' ?>">
                                    <div class="invalid-feedback">
                                        Please provide a valid item name.
                                    </div>
                                </div>
                            </div>

                            <!-- Item Category -->
                            <div class="col-md-6">
                                <label for="item_category" class="form-label required">Item Category</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-collection"></i>
                                    </span>
                                    <select class="form-select" id="item_category" name="item_category" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>" 
                                                    <?= (isset($_POST['item_category']) && $_POST['item_category'] == $cat['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['category']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a category.
                                    </div>
                                </div>
                            </div>

                            <!-- Item Subcategory -->
                            <div class="col-md-6">
                                <label for="item_subcategory" class="form-label required">Item Subcategory</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-diagram-3"></i>
                                    </span>
                                    <select class="form-select" id="item_subcategory" name="item_subcategory" required>
                                        <option value="">Select Subcategory</option>
                                        <?php foreach ($subcats as $sub): ?>
                                            <option value="<?= $sub['id'] ?>" 
                                                    <?= (isset($_POST['item_subcategory']) && $_POST['item_subcategory'] == $sub['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($sub['sub_category']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a subcategory.
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-6">
                                <label for="quantity" class="form-label required">Quantity</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-123"></i>
                                    </span>
                                    <input type="number" class="form-control" id="quantity" name="quantity" 
                                           placeholder="Enter quantity" required min="0" 
                                           value="<?= isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '' ?>">
                                    <div class="invalid-feedback">
                                        Please provide a valid quantity.
                                    </div>
                                </div>
                            </div>

                            <!-- Unit Price -->
                            <div class="col-md-6">
                                <label for="unit_price" class="form-label required">Unit Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-currency-dollar"></i>
                                    </span>
                                    <input type="number" class="form-control" id="unit_price" name="unit_price" 
                                           placeholder="Enter unit price" required min="0" step="0.01" 
                                           value="<?= isset($_POST['unit_price']) ? htmlspecialchars($_POST['unit_price']) : '' ?>">
                                    <div class="invalid-feedback">
                                        Please provide a valid unit price.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="list.php" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Add Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Form Validation Script -->
    <script>
        // Bootstrap form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>

    <script>
        // Custom validation for quantity and unit price (quantity should not be 0 and unit price should be greater than 0)
        document.addEventListener('DOMContentLoaded', () => {
            const qty  = document.getElementById('quantity');
            const price= document.getElementById('unit_price');

            qty.addEventListener('input',  () => {
                qty.setCustomValidity(qty.validity.rangeUnderflow ? 'Quantity must be at least 1.' : '');
            });
            price.addEventListener('input',() => {
                price.setCustomValidity(price.validity.rangeUnderflow ? 'Price must be greater than 0.' : '');
            });
        });
    </script>

</body>
</html>
