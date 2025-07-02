<?php
require '../../config/db.php';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$where = '';
if ($from && $to) {
    $where = "WHERE date BETWEEN '$from' AND '$to'";
}
$sql = "SELECT i.invoice_no, i.date, c.first_name, d.district, i.item_count, i.amount 
        FROM invoice i 
        JOIN customer c ON i.customer = c.id 
        JOIN district d ON c.district = d.id $where";
$invoices = $pdo->query($sql)->fetchAll();
?>
<form method="GET">
    From: <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
    To: <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
    <button type="submit">Filter</button>
</form>
<table>
    <tr>
        <th>Invoice No</th><th>Date</th><th>Customer</th><th>District</th><th>Item Count</th><th>Amount</th>
    </tr>
    <?php foreach ($invoices as $inv): ?>
    <tr>
        <td><?= $inv['invoice_no'] ?></td>
        <td><?= $inv['date'] ?></td>
        <td><?= $inv['first_name'] ?></td>
        <td><?= $inv['district'] ?></td>
        <td><?= $inv['item_count'] ?></td>
        <td><?= $inv['amount'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
