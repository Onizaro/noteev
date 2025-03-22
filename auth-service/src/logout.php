<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Répondre aux requêtes OPTIONS pour éviter les blocages CORS
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// Supprimer le cookie auth_token
setcookie("auth_token", "", time() - 3600, "/", "", false, true);
echo json_encode(["message" => "Logout successful"]);
?>
