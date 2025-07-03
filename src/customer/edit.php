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
<form method="POST">
    <select name="title" required>
        <option value="">Select Title</option>
        <?php foreach (['Mr', 'Mrs', 'Miss', 'Dr'] as $t): ?>
            <option value="<?= $t ?>" <?= $customer['title'] === $t ? 'selected' : '' ?>><?= $t ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="first_name" value="<?= htmlspecialchars($customer['first_name']) ?>" required>
    <input type="text" name="last_name" value="<?= htmlspecialchars($customer['last_name']) ?>" required>
    <input type="text" name="contact_no" value="<?= htmlspecialchars($customer['contact_no']) ?>" required pattern="\d{10}">
    <select name="district" required>
        <?php foreach ($districts as $d): ?>
            <option value="<?= $d['id'] ?>" <?= $customer['district'] == $d['id'] ? 'selected' : '' ?>><?= $d['district'] ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Update Customer</button>
    <?php if (isset($error)) echo "<div>$error</div>"; ?>
</form>
