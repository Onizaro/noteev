<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once "../config/database.php";
require_once "../models/Note.php";
require_once "../auth/checkAuth.php"; // Vérifie que l'utilisateur est connecté

// Vérifie que la requête est de type DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
    exit;
}

// Récupérer les données envoyées en POST (dans le corps de la requête DELETE)
$data = json_decode(file_get_contents("php://input"));
if (!isset($data->id)) {
    http_response_code(400);
    echo json_encode(["error" => "Note ID missing"]);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$note = new Note($db);

// Utiliser l'ID passé dans le corps de la requête
$id = intval($data->id);

// Supprimer la note avec l'ID donné
if ($note->delete($id)) {
    echo json_encode(["message" => "Note deleted"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to delete note"]);
}
?>
