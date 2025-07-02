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
    } else {
        $error = "All fields are required.";
    }
}
?>
<!-- HTML form using Bootstrap -->
<form method="POST">
    <input type="text" name="item_code" placeholder="Item Code" required>
    <input type="text" name="item_name" placeholder="Item Name" required>
    <select name="item_category" required>
        <?php
        $categories = $pdo->query("SELECT * FROM item_category")->fetchAll();
        foreach ($categories as $cat) {
            echo "<option value='{$cat['id']}'>{$cat['category']}</option>";
        }
        ?>
    </select>
    <select name="item_subcategory" required>
        <?php
        $subcats = $pdo->query("SELECT * FROM item_subcategory")->fetchAll();
        foreach ($subcats as $sub) {
            echo "<option value='{$sub['id']}'>{$sub['sub_category']}</option>";
        }
        ?>
    </select>
    <input type="number" name="quantity" placeholder="Quantity" required>
    <input type="number" step="0.01" name="unit_price" placeholder="Unit Price" required>
    <button type="submit">Add Item</button>
    <?php if (isset($error)) echo "<div>$error</div>"; ?>
</form>
