<?php
require "config.php";
require "jwt.php";

$headers = getallheaders();
$token = $headers['Authorization'] ?? '';

if ($token) {
    $decoded = verifyJWT(str_replace("Bearer ", "", $token));
    if ($decoded) {
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE id = ?");
        $stmt->execute([$decoded->user_id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        echo json_encode(["error" => "Token invalide"]);
    }
} else {
    echo json_encode(["error" => "Token manquant"]);
}
?>
