<?php
require_once "../src/models/Note.php";

class NoteController {
    private $db;
    private $note;

    public function __construct($db) {
        $this->db = $db;
        $this->note = new Note($db);
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

    public function deleteNote($id) {
        echo json_encode(["success" => $this->note->delete($id)]);
    }

    
}
?>
