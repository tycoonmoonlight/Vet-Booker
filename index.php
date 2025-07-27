<?php
require 'db_connect.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = sanitizeInput($_POST['service']);
    $date = sanitizeInput($_POST['date']);
    $time = sanitizeInput($_POST['time']);
    $client_name = sanitizeInput($_POST['client_name']);
    $client_email = sanitizeInput($_POST['client_email']);

    // Validation
    $error = '';
    if (empty($service) || empty($date) || empty($time) || empty($client_name) || empty($client_email)) {
        $error = "All fields are required.";
    } elseif (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strtotime($date) < strtotime(date('Y-m-d'))) {
        $error = "Cannot book appointments in the past.";
    } elseif (!validateBooking($pdo, $date, $time)) {
        $error = "Selected time slot is already booked.";
    } else {
        // Insert appointment
        $stmt = $pdo->prepare("INSERT INTO appointments (service, appointment_date, appointment_time, client_name, client_email) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$service, $date, $time, $client_name, $client_email])) {
            $success = "Appointment booked successfully!";
        } else {
            $error = "Failed to book appointment.";
        }
    }
}

$time_slots = generateTimeSlots();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VetBooker - Book Appointment</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">VetBooker - Book an Appointment</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="POST" class="col-md-6 mx-auto">
            <div class="mb-3">
                <label for="service" class="form-label">Service</label>
                <select class="form-select" id="service" name="service" required>
                    <option value="">Select a service</option>
                    <option value="Checkup">Checkup</option>
                    <option value="Vaccination">Vaccination</option>
                    <option value="Surgery">Surgery</option>
                    <option value="Grooming">Grooming</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <select class="form-select" id="time" name="time" required>
                    <option value="">Select a time</option>
                    <?php foreach ($time_slots as $slot): ?>
                        <option value="<?php echo $slot; ?>"><?php echo $slot; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="client_name" class="form-label">Name</label>
                <input type="text" class="form-control" id="client_name" name="client_name" required>
            </div>
            <div class="mb-3">
                <label for="client_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="client_email" name="client_email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
        </form>
    </div>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>