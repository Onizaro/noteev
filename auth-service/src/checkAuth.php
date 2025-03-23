<?php
require 'db.php';
require 'jwt.php';

// Autoriser CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Répondre aux requêtes OPTIONS pour éviter les blocages CORS
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// Récupérer le token depuis le header Authorization ou le cookie
$headers = getallheaders();
$token = null;

if (isset($headers['Authorization'])) {
    $token = str_replace("Bearer ", "", $headers['Authorization']);
} elseif (isset($_COOKIE["auth_token"])) {
    $token = $_COOKIE["auth_token"];
}

// Vérifier si un token est présent
if (!$token) {
    http_response_code(401);
    header("Content-Type: application/json");
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

// Vérifier la validité du JWT
$userData = verify_jwt($token);

if (!$userData) {
    http_response_code(401);
    header("Content-Type: application/json");
    echo json_encode(["error" => "Invalid token"]);
    exit;
}

// Retourner les données de l'utilisateur
http_response_code(200);
header("Content-Type: application/json");
echo json_encode(["user" => $userData]);
?>
