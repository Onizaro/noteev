<?php
require "config.php";
require "jwt.php";

$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

$stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $token = createJWT($user['id']);
    echo json_encode(["token" => $token]);
} else {
    echo json_encode(["error" => "Identifiants incorrects"]);
}
?>
