<?php
require '../../config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_no = $_POST['contact_no'];
    $district = $_POST['district'];

    // Simple validation
    if ($title && $first_name && $last_name && $contact_no && $district) {
        $stmt = $pdo->prepare("INSERT INTO customer (title, first_name, last_name, contact_no, district) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $first_name, $last_name, $contact_no, $district]);
        header('Location: list.php');
    } else {
        $error = "All fields are required.";
    }
}
?>
<!-- HTML form using Bootstrap -->
<form method="POST">
    <select name="title" required>
        <option value="">Select Title</option>
        <option>Mr</option>
        <option>Mrs</option>
        <option>Miss</option>
        <option>Dr</option>
    </select>
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="text" name="contact_no" placeholder="Contact Number" required pattern="\d{10}">
    <select name="district" required>
        <!-- Populate from district table -->
        <?php
        $districts = $pdo->query("SELECT * FROM district WHERE active='yes'")->fetchAll();
        foreach ($districts as $d) {
            echo "<option value='{$d['id']}'>{$d['district']}</option>";
        }
        ?>
    </select>
    <button type="submit">Add Customer</button>
    <?php if (isset($error)) echo "<div>$error</div>"; ?>
</form>
