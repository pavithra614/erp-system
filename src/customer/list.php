<?php
require '../../config/db.php';
$customers = $pdo->query("SELECT c.*, d.district as district_name FROM customer c JOIN district d ON c.district = d.id")->fetchAll();
?>
<table>
    <tr>
        <th>Title</th><th>First Name</th><th>Last Name</th><th>Contact No</th><th>District</th>
    </tr>
    <?php foreach ($customers as $cust): ?>
    <tr>
        <td><?= htmlspecialchars($cust['title']) ?></td>
        <td><?= htmlspecialchars($cust['first_name']) ?></td>
        <td><?= htmlspecialchars($cust['last_name']) ?></td>
        <td><?= htmlspecialchars($cust['contact_no']) ?></td>
        <td><?= htmlspecialchars($cust['district_name']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
