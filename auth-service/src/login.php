<?php
require 'db.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data["identifier"], $data["password"])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing identifier (username/email) or password"]);
    exit;
}

$identifier = $data["identifier"]; // Peut être un username ou un email
$password = $data["password"];

// Vérification avec username ou email
$stmt = $pdo->prepare("SELECT password FROM users WHERE username = ? OR email = ?");
$stmt->execute([$identifier, $identifier]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {
    echo json_encode(["message" => "Connexion successful"]);
} else {
    http_response_code(401);
    echo json_encode(["error" => "Invalid credentials"]);
}
?>
