<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require "vendor/autoload.php";

$key = "secretkey";

function createJWT($userId) {
    global $key;
    $payload = [
        "user_id" => $userId,
        "exp" => time() + 3600 
    ];
    return JWT::encode($payload, $key, 'HS256');
}

function verifyJWT($token) {
    global $key;
    try {
        return JWT::decode($token, new Key($key, 'HS256'));
    } catch (Exception $e) {
        return false;
    }
}
?>
