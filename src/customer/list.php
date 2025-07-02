<?php
$page_title = "Customer List";
require '../../config/db.php';
require '../../includes/header.php';

// Search functionality
$search = $_GET['search'] ?? '';
$district_filter = $_GET['district'] ?? '';

$sql = "SELECT c.*, d.district as district_name FROM customer c JOIN district d ON c.district = d.id";
$params = [];

$where_conditions = [];
if (!empty($search)) {
    $where_conditions[] = "(c.first_name LIKE ? OR c.last_name LIKE ? OR c.contact_no LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
}

if (!empty($district_filter)) {
    $where_conditions[] = "c.district = ?";
    $params[] = $district_filter;
}

if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}

$sql .= " ORDER BY c.first_name, c.last_name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$customers = $stmt->fetchAll();

// Get districts for filter
$districts = $pdo->query("SELECT * FROM district WHERE active='yes' ORDER BY district")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Customer List</h2>
    <a href="add.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Customer
    </a>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" id="search" class="form-control"
                       placeholder="Search by name or contact number"
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-4">
                <label for="district" class="form-label">Filter by District</label>
                <select name="district" id="district" class="form-select">
                    <option value="">All Districts</option>
                    <?php foreach ($districts as $d): ?>
                        <option value="<?php echo $d['id']; ?>"
                                <?php echo ($district_filter == $d['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($d['district']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search me-1"></i>Search
                </button>
                <a href="list.php" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Results Section -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Customers
            <span class="badge bg-primary"><?php echo count($customers); ?></span>
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($customers)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No customers found</h5>
                <p class="text-muted">
                    <?php if (!empty($search) || !empty($district_filter)): ?>
                        Try adjusting your search criteria or <a href="list.php">view all customers</a>.
                    <?php else: ?>
                        <a href="add.php" class="btn btn-primary">Add your first customer</a>
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Contact Number</th>
                            <th>District</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $cust): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cust['title']); ?></td>
                            <td><?php echo htmlspecialchars($cust['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($cust['last_name']); ?></td>
                            <td>
                                <a href="tel:<?php echo $cust['contact_no']; ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($cust['contact_no']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($cust['district_name']); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="edit.php?id=<?php echo $cust['id']; ?>"
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger"
                                            onclick="confirmDelete(<?php echo $cust['id']; ?>, '<?php echo htmlspecialchars($cust['first_name'] . ' ' . $cust['last_name']); ?>')"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm('Are you sure you want to delete customer "' + name + '"? This action cannot be undone.')) {
        window.location.href = 'delete.php?id=' + id;
    }
}
</script>

<?php require '../../includes/footer.php'; ?>
