<?php
$host = "localhost";
$db   = "lamp";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = intval($_GET['id'] ?? 0);
    if ($id > 0) {
        // Toggle lamp state (0 → 1, 1 → 0)
        $stmt = $pdo->prepare("UPDATE lamps SET is_lit = NOT is_lit WHERE lamp_number = ?");
        $stmt->execute([$id]);
        echo json_encode(["status" => "ok", "lamp" => $id]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid lamp ID"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
