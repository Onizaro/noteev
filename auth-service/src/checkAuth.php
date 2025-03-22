<?php
require 'db.php';
require 'jwt.php';

// Autoriser CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Répondre aux requêtes OPTIONS pour éviter les blocages CORS
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// Vérifier la présence du cookie auth_token
if (!isset($_COOKIE["auth_token"])) {
    http_response_code(401);
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

$token = $_COOKIE["auth_token"];
$userData = verify_jwt($token); // Vérifie le JWT

if (!$userData) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token"]);
    exit;
}

// Retourner les données de l'utilisateur
echo json_encode(["user" => $userData]);
?>
