<?php
require_once '../src/config/database.php';
require_once '../src/controllers/NoteController.php';
require_once '../src/utils/AuthMiddleware.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Initialisation de la connexion à la base de données
$database = new Database();
$pdo = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Créer un contrôleur
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

// Route pour ajouter une note
if ($method === 'POST' && $requestUri === '/api/addNote.php') {
    session_start();

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

    // Debugging: Afficher le contenu de $userData
    error_log(print_r($userData, true));

    // Vérifier si la clé 'id' existe dans $userData
    if (!isset($userData["user"]["id"])) {
        http_response_code(401);
        echo json_encode(["error" => "Token invalide: id manquant"]);
        exit;
    }

    // Ajoute l'ID utilisateur à la requête avant de l'enregistrer
    $_POST['id'] = $userData["user"]["id"];
    $controller->createNote();
    exit;
}

// Routes pour récupérer les notes
elseif ($method === 'GET' && $requestUri === '/api/getNotes.php') {
    session_start();

    $token = getAuthToken();

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

    // Debugging: Afficher le contenu de $userData
    error_log(print_r($userData, true));

    // Vérifier si la clé 'id' existe dans $userData
    if (!isset($userData["user"]["id"])) {
        http_response_code(401);
        echo json_encode(["error" => "Token invalide: id manquant", "userData" => $userData]);
        exit;
    }
    // Récupérer les notes de l'utilisateur authentifié
    $controller->getNotes($userData["user"]["id"]);
    exit;
}

// Route pour supprimer une note
elseif ($method === 'DELETE' && preg_match('/^\/api\/deleteNote\.php\?id=(\d+)$/', $requestUri, $matches)) {
    session_start();

    $token = getAuthToken();

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

    // Vérifier si l'utilisateur est autorisé à supprimer la note
    $noteId = (int) $matches[1];
    $userId = $userData["user"]["id"];

    // Vérifier si la note appartient à l'utilisateur
    if (!$controller->checkNoteOwnership($noteId, $userId)) {
        http_response_code(403);
        echo json_encode(["error" => "Vous ne pouvez pas supprimer cette note"]);
        exit;
    }

    // Supprimer la note
    $controller->deleteNote($noteId);

    echo json_encode(["message" => "Note supprimée avec succès"]);
    exit;
} else {
    http_response_code(404);
    echo json_encode(["error" => "Route non trouvée"]);
    exit;
}
?>
