<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

// Charger les variables d'environnement
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Récupérer la clé secrète depuis l'environnement
$secret_key = $_ENV["SECRET_KEY"] ?? die("SECRET_KEY non définie");


function generate_jwt($user) {
    global $secret_key;
    
    $payload = [
        "iat" => time(), 
        "exp" => time() + (60 * 60 * 24), 
        "user" => $user
    ];

    return JWT::encode($payload, $secret_key, 'HS256');
}

function verify_jwt($token) {
    global $secret_key;

    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        return (array) $decoded->user;
    } catch (Exception $e) {
        return false;
    }
}
?>
