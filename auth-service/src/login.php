<?php
require 'db.php';
require 'jwt.php'; // Bibliothèque pour JWT

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (empty($data["identifier"]) || empty($data["password"])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing identifier or password"]);
    exit;
}

$identifier = trim($data["identifier"]);
$password = $data["password"];

$stmt = $pdo->prepare("SELECT id, username, email, password FROM users WHERE username = ? OR email = ?");
$stmt->execute([$identifier, $identifier]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {
    $token = generate_jwt(["id" => $user["id"], "username" => $user["username"]]);
    setcookie("auth_token", $token, time() + 3600, "/", "", false, true);
    echo json_encode(["message" => "Login successful"]);
} else {
    http_response_code(401);
    echo json_encode(["error" => "Invalid credentials"]);
}

?>
