<?php
require '../../config/db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM item WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: list.php');
exit;
?>
