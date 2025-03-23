<?php
require_once '../src/config/database.php';
require_once '../src/controllers/NoteController.php';
require_once '../src/utils/AuthMiddleware.php';

header("Content-Type: application/json");

// Initialisation de la connexion à la base de données
$database = new Database();
$pdo = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Corrige la comparaison de l'URL

$controller = new NoteController($pdo);

// Fonction pour récupérer le token depuis Authorization ou le cookie
function getAuthToken() {
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        return str_replace("Bearer ", "", $headers['Authorization']);
    } elseif (isset($_COOKIE["auth_token"])) {
        return $_COOKIE["auth_token"];
    }
    return null;
}

// Routes protégées par JWT
if ($method === 'POST' && $requestUri === '/notes') {
    $token = getAuthToken();

    // Vérifier si un token est présent
    if (!$token) {
        http_response_code(401);
        echo json_encode(["error" => "Token manquant"]);
        exit;
    }

    $userData = AuthMiddleware::verifyToken($token);

    if (!$userData) {
        http_response_code(401);
        echo json_encode(["error" => "Token invalide"]);
        exit;
    }

    // Ajoute l'ID utilisateur à la requête avant de l'enregistrer
    $_POST['user_id'] = $userData['user_id'];
    $controller->createNote();
    exit;
}

// Récupérer les notes d'un utilisateur (protégé par JWT)
elseif ($method === 'GET' && $requestUri === '/notes') {
    $token = getAuthToken();

    // Vérifier si un token est présent
    if (!$token) {
        http_response_code(401);
        echo json_encode(["error" => "Token manquant"]);
        exit;
    }

    $userData = AuthMiddleware::verifyToken($token);

    if (!$userData) {
        http_response_code(401);
        echo json_encode(["error" => "Token invalide"]);
        exit;
    }

    // Récupérer les notes de l'utilisateur authentifié
    $controller->getNotes($userData['user_id']);
    exit;
} else {
    http_response_code(404);
    echo json_encode(["error" => "Route non trouvée"]);
    exit;
}
