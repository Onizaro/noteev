<?php
require_once "../config/database.php";
require_once "../models/Note.php";
require_once "../auth/checkAuth.php"; // Vérifie que l'utilisateur est connecté

$data = json_decode(file_get_contents("php://input"));
if (!$data || !isset($data->content) || trim($data->content) === "") {
    http_response_code(400);
    echo json_encode(["error" => "Invalid content"]);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$note = new Note($db);
$note->user_id = $_SESSION['user_id'];
$note->content = htmlspecialchars(strip_tags($data->content));

if ($note->create()) {
    http_response_code(201);
    echo json_encode(["message" => "Note added"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to add note"]);
}
?>
