<?php
require_once "../src/models/Note.php";

class NoteController {
    private $db;
    private $note;
    private $pdo;

    // Le constructeur prend désormais un objet PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;  // Assigner correctement la connexion PDO
        $this->note = new Note($pdo);  // Passer le PDO à l'objet Note
    }

    public function createNote() {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->note->user_id = $data["user_id"];
        $this->note->content = $data["content"];
        echo json_encode(["success" => $this->note->create()]);
    }

    public function getNotes($user_id) {
        echo json_encode($this->note->getUserNotes($user_id));
    }

    public function deleteNote($noteId) {
        $stmt = $this->pdo->prepare("DELETE FROM notes WHERE id = :id");
        $stmt->bindParam(':id', $noteId);
        $stmt->execute();
    }

    public function checkNoteOwnership($noteId, $userId) {
        // Vérifier dans la base de données si la note appartient à l'utilisateur
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM notes WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $noteId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    }
}
?>
