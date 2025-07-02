<?php
require '../../config/db.php';
$items = $pdo->query("SELECT i.*, c.category, s.sub_category FROM item i 
    JOIN item_category c ON i.item_category = c.id 
    JOIN item_subcategory s ON i.item_subcategory = s.id")->fetchAll();
?>
<table>
    <tr>
        <th>Code</th><th>Name</th><th>Category</th><th>Subcategory</th><th>Quantity</th><th>Unit Price</th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['item_code']) ?></td>
        <td><?= htmlspecialchars($item['item_name']) ?></td>
        <td><?= htmlspecialchars($item['category']) ?></td>
        <td><?= htmlspecialchars($item['sub_category']) ?></td>
        <td><?= htmlspecialchars($item['quantity']) ?></td>
        <td><?= htmlspecialchars($item['unit_price']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
