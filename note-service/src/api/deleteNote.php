<?php
require_once "../config/database.php";
require_once "../models/Note.php";
require_once "../auth/checkAuth.php"; // Vérifie que l'utilisateur est connecté

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Note ID missing"]);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$note = new Note($db);

$id = intval($_GET['id']);
if ($note->delete($id)) {
    echo json_encode(["message" => "Note deleted"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to delete note"]);
}
?>
