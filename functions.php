<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function validateBooking($pdo, $date, $time) {
    // Check if time slot is already booked
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_date = ? AND appointment_time = ? AND status != 'cancelled'");
    $stmt->execute([$date, $time]);
    return $stmt->fetchColumn() == 0; // True if slot is available
}

function generateTimeSlots() {
    $slots = [];
    $start = strtotime("09:00"); // Clinic opens at 9 AM
    $end = strtotime("17:00");   // Clinic closes at 5 PM
    $interval = 30 * 60;         // 30-minute intervals

    for ($time = $start; $time <= $end; $time += $interval) {
        $slots[] = date("H:i", $time);
    }
    return $slots;
}

function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}
?>