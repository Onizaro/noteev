import { useState, useEffect } from "react";
import "./Home.css";
import NavBar from "../components/NavBar";
import PlusIcon from "../icons/plus.svg";
import Note from "../components/Note";
import { useNavigate } from "react-router-dom";

const Home = () => {
  const [notes, setNotes] = useState([]);
  const [newNote, setNewnote] = useState("");
  const [searchTerm, setSearchTerm] = useState("");
  const [user, setUser] = useState(null);
  const [isLogged, setIsLogged] = useState(false);
  const navigate = useNavigate();

  // Récupérer les notes de l'utilisateur
  useEffect(() => {
    const fetchNotes = async () => {
      try {
        const response = await fetch("http://localhost:9999/api/getNotes.php", { credentials: "include" });
        const data = await response.json();
        console.log("Réponse de l'API pour les notes:", data);
        if (response.ok && data) {
          setNotes(data);  // Assure-toi que data est un tableau
        } else {
          console.log("Aucune note disponible.");
          setNotes([]);
        }
      } catch (error) {
        console.error("Erreur lors de la récupération des notes", error);
      }
    };
    

    const checkLogin = async () => {
      try {
        const response = await fetch("http://localhost:4665/checkAuth.php", { credentials: "include" });
        const data = await response.json();
        if (response.ok) {
          setUser(data.user);
          setIsLogged(true);
          fetchNotes();
        } else {
          setIsLogged(false);
          navigate("/login");
        }
      } catch (error) {
        console.error("Erreur lors de la vérification de l'authentification", error);
      }
    };

    checkLogin();
  }, [navigate]);

  // Ajouter une note via l'API
  const addNote = async () => {
    if (newNote.trim() !== "") {
      try {
        const response = await fetch("http://localhost:9999/api/addNote.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          credentials: "include", // Important pour la session
          body: JSON.stringify({ 
            user_id: user.id,
            content: newNote }),
        });
  
        console.log("Response headers:", response.headers);
  
        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.error || `Erreur HTTP: ${response.status}`);
        }
  
        const data = await response.json();
        setNotes([...notes, data]); 
        setNewnote("");
      } catch (error) {
        console.error("Erreur lors de l'ajout de la note", error);
      }
    }
  };
  

  // Supprimer une note via l'API
  const deleteNote = async (id) => {
    if (!window.confirm("Are you sure?")) return;

    try {
      const response = await fetch(`http://localhost:9999/api/deleteNote.php?id=${id}`, {
        method: "DELETE",
        credentials: "include",
      });

      if (response.ok) {
        if (Array.isArray(notes)){
          setNotes(notes.filter((note) => note.id !== id));
        };
        
      }
    } catch (error) {
      console.error("Erreur lors de la suppression de la note", error);
    }
  };
  console.log(notes);
  const filteredNotes = Array.isArray(notes)
  ? notes.filter((note) =>
      note.content.toLowerCase().includes(searchTerm.toLowerCase()) || searchTerm === ""
    )
  : [];


  return (
    <div className="App">
      <NavBar searchTerm={searchTerm} setSearchTerm={setSearchTerm} isLogged={isLogged} setIsLogged={setIsLogged} />

      <header className="header">
        <input
          type="text"
          className="write"
          placeholder="Write here..."
          value={newNote}
          onChange={(e) => setNewnote(e.target.value)}
          onKeyDown={(e) => e.key === "Enter" && addNote()}
        />

        <div className="add-note">
          <button className="key" type="button" onClick={addNote}>
            <img className="plus-icon" src={PlusIcon} alt="Add Note" />
            Add
          </button>
        </div>
      </header>

      <section className="notes">
        {filteredNotes.length > 0 && <h1 className="mynotes">My notes</h1>}
        <ul className="note-list">
          {filteredNotes.map((note) => (
            <Note key={note.id} note={note.content} onDelete={() => deleteNote(note.id)} />
          ))}
        </ul>
      </section>

      <footer>
        <div id="fixed-buttons">
          <button className="key" onClick={() => window.scrollTo({ top: 0, behavior: "smooth" })}>↑</button>
          <button className="key" onClick={() => window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" })}>↓</button>
        </div>
      </footer>
    </div>
  );
};

export default Home;
