<?php
require '../../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die('Customer ID is required.');
}

// Fetch existing customer data
$stmt = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
$stmt->execute([$id]);
$customer = $stmt->fetch();

if (!$customer) {
    die('Customer not found.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_no = $_POST['contact_no'];
    $district = $_POST['district'];

    if ($title && $first_name && $last_name && $contact_no && $district) {
        $update = $pdo->prepare("UPDATE customer SET title=?, first_name=?, last_name=?, contact_no=?, district=? WHERE id=?");
        $update->execute([$title, $first_name, $last_name, $contact_no, $district, $id]);
        header('Location: list.php');
        exit;
    } else {
        $error = "All fields are required.";
    }
}

// Fetch districts for dropdown
$districts = $pdo->query("SELECT * FROM district WHERE active='yes'")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
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
                    <i class="bi bi-pencil-square fs-3 text-warning me-2"></i>
                    <h2 class="mb-0">Edit Customer</h2>
                </div>
                <a href="list.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>

            <!-- Form Card -->
            <div class="card">
                <div class="card-header bg-primary text-dark py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-gear me-2"></i>
                        <span class="fw-semibold">Customer Information</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?= htmlspecialchars($error) ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate id="customerForm">
                        <div class="row g-3">
                            <!-- Title -->
                            <div class="col-md-6">
                                <label for="title" class="form-label required">Title</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-badge"></i>
                                    </span>
                                    <select class="form-select" id="title" name="title" required>
                                        <option value="">Select Title</option>
                                        <?php foreach (['Mr', 'Mrs', 'Miss', 'Dr'] as $t): ?>
                                            <option value="<?= $t ?>" <?= $customer['title'] === $t ? 'selected' : '' ?>><?= $t ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a title.
                                    </div>
                                </div>
                            </div>

                            <!-- First Name -->
                            <div class="col-md-6">
                                <label for="first_name" class="form-label required">First Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           placeholder="Enter first name" required 
                                           value="<?= htmlspecialchars($customer['first_name']) ?>">
                                    <div class="invalid-feedback">
                                        Please provide a valid first name.
                                    </div>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6">
                                <label for="last_name" class="form-label required">Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           placeholder="Enter last name" required 
                                           value="<?= htmlspecialchars($customer['last_name']) ?>">
                                    <div class="invalid-feedback">
                                        Please provide a valid last name.
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Number -->
                            <div class="col-md-6">
                                <label for="contact_no" class="form-label required">Contact Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="tel" class="form-control" id="contact_no" name="contact_no" 
                                           placeholder="Enter 10-digit contact number" required 
                                           pattern="[0-9]{10}" maxlength="10"
                                           value="<?= htmlspecialchars($customer['contact_no']) ?>">
                                    <div class="invalid-feedback">
                                        Please provide a valid 10-digit contact number.
                                    </div>
                                </div>
                            </div>

                            <!-- District -->
                            <div class="col-md-12">
                                <label for="district" class="form-label required">District</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-geo-alt"></i>
                                    </span>
                                    <select class="form-select" id="district" name="district" required>
                                        <option value="">Select District</option>
                                        <?php foreach ($districts as $d): ?>
                                            <option value="<?= $d['id'] ?>" <?= $customer['district'] == $d['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($d['district']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a district.
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
                                <i class="bi bi-check-circle me-1"></i>Update Customer
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

    <!-- Custom Validation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('customerForm');
            const firstName = document.getElementById('first_name');
            const lastName = document.getElementById('last_name');
            const contactNo = document.getElementById('contact_no');

            // Name validation (only letters and spaces)
            function validateName(input) {
                const namePattern = /^[a-zA-Z\s]+$/;
                if (!namePattern.test(input.value)) {
                    input.setCustomValidity('Name should contain only letters and spaces.');
                } else {
                    input.setCustomValidity('');
                }
            }

            // Contact number validation (exactly 10 digits)
            function validateContact(input) {
                const contactPattern = /^[0-9]{10}$/;
                if (!contactPattern.test(input.value)) {
                    input.setCustomValidity('Contact number must be exactly 10 digits.');
                } else {
                    input.setCustomValidity('');
                }
            }

            // Real-time validation
            firstName.addEventListener('input', function() {
                validateName(this);
            });

            lastName.addEventListener('input', function() {
                validateName(this);
            });

            contactNo.addEventListener('input', function() {
                validateContact(this);
                // Auto-format: remove non-digits
                this.value = this.value.replace(/\D/g, '');
            });

            // Prevent form submission if validation fails
            form.addEventListener('submit', function(event) {
                validateName(firstName);
                validateName(lastName);
                validateContact(contactNo);
                
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    </script>
</body>
</html>
