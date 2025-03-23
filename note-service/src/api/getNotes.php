<?php
require_once "../config/database.php";
require_once "../models/Note.php";
require_once "../auth/checkAuth.php"; // Vérifie que l'utilisateur est connecté

$database = new Database();
$db = $database->getConnection();
$note = new Note($db);

$user_id = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté
$notes = $note->getUserNotes($user_id);

echo json_encode(["notes" => $notes]);
?>
