<?php
require '../../config/db.php';
$sql = "SELECT DISTINCT i.item_name, c.category, s.sub_category, i.quantity 
        FROM item i 
        JOIN item_category c ON i.item_category = c.id 
        JOIN item_subcategory s ON i.item_subcategory = s.id";
$items = $pdo->query($sql)->fetchAll();
?>
<table>
    <tr>
        <th>Item Name</th><th>Category</th><th>Subcategory</th><th>Quantity</th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr>
        <td><?= $item['item_name'] ?></td>
        <td><?= $item['category'] ?></td>
        <td><?= $item['sub_category'] ?></td>
        <td><?= $item['quantity'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
