<?php
$page_title = "Add Customer";
require '../../config/db.php';
require '../../includes/header.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $contact_no = trim($_POST['contact_no'] ?? '');
    $district = trim($_POST['district'] ?? '');

    // Enhanced validation
    if (empty($title)) {
        $errors['title'] = "Title is required.";
    }

    if (empty($first_name)) {
        $errors['first_name'] = "First name is required.";
    } elseif (strlen($first_name) < 2) {
        $errors['first_name'] = "First name must be at least 2 characters.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $first_name)) {
        $errors['first_name'] = "First name can only contain letters and spaces.";
    }

    if (empty($last_name)) {
        $errors['last_name'] = "Last name is required.";
    } elseif (strlen($last_name) < 2) {
        $errors['last_name'] = "Last name must be at least 2 characters.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $last_name)) {
        $errors['last_name'] = "Last name can only contain letters and spaces.";
    }

    if (empty($contact_no)) {
        $errors['contact_no'] = "Contact number is required.";
    } elseif (!preg_match("/^[0-9]{10}$/", $contact_no)) {
        $errors['contact_no'] = "Contact number must be exactly 10 digits.";
    } else {
        // Check if contact number already exists
        $stmt = $pdo->prepare("SELECT id FROM customer WHERE contact_no = ?");
        $stmt->execute([$contact_no]);
        if ($stmt->fetch()) {
            $errors['contact_no'] = "This contact number is already registered.";
        }
    }

    if (empty($district)) {
        $errors['district'] = "District is required.";
    }

    // If no errors, insert the customer
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO customer (title, first_name, last_name, contact_no, district) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $first_name, $last_name, $contact_no, $district]);
            $success = "Customer added successfully!";
            // Clear form data
            $title = $first_name = $last_name = $contact_no = $district = '';
        } catch (PDOException $e) {
            $errors['general'] = "Error adding customer. Please try again.";
        }
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Add New Customer</h4>
            </div>
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($errors['general'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $errors['general']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <select name="title" id="title" class="form-select <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" required>
                                <option value="">Select Title</option>
                                <option value="Mr" <?php echo (isset($title) && $title === 'Mr') ? 'selected' : ''; ?>>Mr</option>
                                <option value="Mrs" <?php echo (isset($title) && $title === 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
                                <option value="Miss" <?php echo (isset($title) && $title === 'Miss') ? 'selected' : ''; ?>>Miss</option>
                                <option value="Dr" <?php echo (isset($title) && $title === 'Dr') ? 'selected' : ''; ?>>Dr</option>
                            </select>
                            <?php if (isset($errors['title'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['title']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name"
                                   class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>"
                                   placeholder="Enter first name"
                                   value="<?php echo htmlspecialchars($first_name ?? ''); ?>"
                                   required>
                            <?php if (isset($errors['first_name'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name"
                                   class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>"
                                   placeholder="Enter last name"
                                   value="<?php echo htmlspecialchars($last_name ?? ''); ?>"
                                   required>
                            <?php if (isset($errors['last_name'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_no" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="tel" name="contact_no" id="contact_no"
                                   class="form-control <?php echo isset($errors['contact_no']) ? 'is-invalid' : ''; ?>"
                                   placeholder="Enter 10-digit contact number"
                                   value="<?php echo htmlspecialchars($contact_no ?? ''); ?>"
                                   pattern="[0-9]{10}"
                                   maxlength="10"
                                   required>
                            <?php if (isset($errors['contact_no'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['contact_no']; ?></div>
                            <?php endif; ?>
                            <div class="form-text">Enter exactly 10 digits without spaces or special characters</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="district" class="form-label">District <span class="text-danger">*</span></label>
                        <select name="district" id="district" class="form-select <?php echo isset($errors['district']) ? 'is-invalid' : ''; ?>" required>
                            <option value="">Select District</option>
                            <?php
                            $districts = $pdo->query("SELECT * FROM district WHERE active='yes' ORDER BY district")->fetchAll();
                            foreach ($districts as $d) {
                                $selected = (isset($district) && $district == $d['id']) ? 'selected' : '';
                                echo "<option value='{$d['id']}' {$selected}>{$d['district']}</option>";
                            }
                            ?>
                        </select>
                        <?php if (isset($errors['district'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['district']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="list.php" class="btn btn-secondary me-md-2">
                            <i class="fas fa-list me-2"></i>View Customers
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Add Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Contact number validation
document.getElementById('contact_no').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Name validation
document.getElementById('first_name').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
});

document.getElementById('last_name').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
});
</script>

<?php require '../../includes/footer.php'; ?>
