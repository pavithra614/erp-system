<?php
require '../../config/db.php';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$where = '';
if ($from && $to) {
    $where = "WHERE i.date BETWEEN '$from' AND '$to'";
}
$sql = "SELECT im.invoice_no, i.date, c.first_name, itm.item_name, itm.item_code, 
        ic.category, im.unit_price 
        FROM invoice_master im 
        JOIN invoice i ON im.invoice_no = i.invoice_no 
        JOIN customer c ON i.customer = c.id 
        JOIN item itm ON im.item_id = itm.id 
        JOIN item_category ic ON itm.item_category = ic.id $where";
$items = $pdo->query($sql)->fetchAll();
?>
<form method="GET">
    From: <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
    To: <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
    <button type="submit">Filter</button>
</form>
<table>
    <tr>
        <th>Invoice No</th><th>Date</th><th>Customer</th><th>Item Name</th><th>Item Code</th><th>Category</th><th>Unit Price</th>
    </tr>
    <?php foreach ($items as $row): ?>
    <tr>
        <td><?= $row['invoice_no'] ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['first_name'] ?></td>
        <td><?= $row['item_name'] ?></td>
        <td><?= $row['item_code'] ?></td>
        <td><?= $row['category'] ?></td>
        <td><?= $row['unit_price'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
