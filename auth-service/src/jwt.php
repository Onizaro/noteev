<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "your_secret_key"; // Mets une clé secrète forte ici

function generate_jwt($user) {
    global $secret_key;
    
    $payload = [
        "iat" => time(), // Temps d'émission
        "exp" => time() + (60 * 60 * 24), // Expiration (24h)
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
