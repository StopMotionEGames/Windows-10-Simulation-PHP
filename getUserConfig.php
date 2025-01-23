<?php
require 'db.php';

$username = $_POST['username'] ?? '';

$stmt = $pdo->prepare("SELECT use_pin FROM users_data WHERE name = :name");
$stmt->execute(['name' => $username]);
$use_pin = $stmt->fetchColumn();

echo json_encode(['use_pin' => $use_pin]);