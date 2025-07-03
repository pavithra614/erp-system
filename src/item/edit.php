<?php
require '../../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die('Item ID is required.');
}

// Fetch existing item data
$stmt = $pdo->prepare("SELECT * FROM item WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    die('Item not found.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_code = $_POST['item_code'];
    $item_name = $_POST['item_name'];
    $item_category = $_POST['item_category'];
    $item_subcategory = $_POST['item_subcategory'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];

    if ($item_code && $item_name && $item_category && $item_subcategory && $quantity && $unit_price) {
        $update = $pdo->prepare("UPDATE item SET item_code=?, item_name=?, item_category=?, item_subcategory=?, quantity=?, unit_price=? WHERE id=?");
        $update->execute([$item_code, $item_name, $item_category, $item_subcategory, $quantity, $unit_price, $id]);
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
<form method="POST">
    <input type="text" name="item_code" value="<?= htmlspecialchars($item['item_code']) ?>" required>
    <input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required>
    <select name="item_category" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $item['item_category'] == $cat['id'] ? 'selected' : '' ?>><?= $cat['category'] ?></option>
        <?php endforeach; ?>
    </select>
    <select name="item_subcategory" required>
        <?php foreach ($subcats as $sub): ?>
            <option value="<?= $sub['id'] ?>" <?= $item['item_subcategory'] == $sub['id'] ? 'selected' : '' ?>><?= $sub['sub_category'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" required>
    <input type="number" step="0.01" name="unit_price" value="<?= htmlspecialchars($item['unit_price']) ?>" required>
    <button type="submit">Update Item</button>
    <?php if (isset($error)) echo "<div>$error</div>"; ?>
</form>
