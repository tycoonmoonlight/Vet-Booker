<?php
$host = 'localhost';
$dbname = 'vetbooker';
$username = 'root'; // Update with your DB username
$password = '';     // Update with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>