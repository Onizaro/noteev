<?php
// Inclure le fichier Note.php et la connexion à la base de données
include_once 'config/database.php';
include_once 'models/Note.php';

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

// Initialiser la base de données et la classe Note
$database = new Database();
$db = $database->getConnection();
$noteModel = new Note($db);

// Vérifier l'authentification de l'utilisateur
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilisateur non authentifié']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les notes de l'utilisateur
$notes = $noteModel->getUserNotes($user_id);

// Définir le type de contenu comme JSON
header('Content-Type: application/json');

// Vérifier si des notes ont été récupérées et renvoyer la réponse JSON
if (!empty($notes)) {
    echo json_encode(['notes' => $notes]);
} else {
    echo json_encode(['notes' => []]); // Aucune note trouvée
}
?>
