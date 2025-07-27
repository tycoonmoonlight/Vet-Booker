<?php
require 'db_connect.php';
require 'functions.php';
requireLogin();

// Update appointment status
if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header("Location: admin.php");
    exit;
}

// Delete appointment
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit;
}

// Fetch all appointments
$stmt = $pdo->query("SELECT * FROM appointments ORDER BY appointment_date, appointment_time");
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VetBooker - Admin Panel</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">VetBooker - Admin Panel</h1>
        <div class="text-end mb-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <h2>Appointments</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Client Name</th>
                        <th>Client Email</th>
                        <th>Status</th>
                        <th>Booked On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo $appointment['id']; ?></td>
                            <td><?php echo htmlspecialchars($appointment['service']); ?></td>
                            <td><?php echo $appointment['appointment_date']; ?></td>
                            <td><?php echo $appointment['appointment_time']; ?></td>
                            <td><?php echo htmlspecialchars($appointment['client_name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['client_email']); ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?php echo $appointment['id']; ?>">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="pending" <?php echo $appointment['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo $appointment['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="cancelled" <?php echo $appointment['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td><?php echo $appointment['created_at']; ?></td>
                            <td>
                                <a href="admin.php?delete=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>