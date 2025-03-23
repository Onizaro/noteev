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
  const [isLogged, setIsLogged] = useState(null);
  const navigate = useNavigate();

  // Fetch user notes
  const fetchNotes = async () => {
    try {
      const response = await fetch("http://localhost:9999/api/getNotes.php", { credentials: "include" });
      const data = await response.json();
      console.log("API response for notes:", data);
      if (response.ok && data) {
        setNotes(data);  // Make sure 'data' is an array
      } else {
        console.log("No notes available.");
        setNotes([]);
      }
    } catch (error) {
      console.error("Error fetching notes", error);
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
      }
    } catch (error) {
      console.error("Error checking authentication", error);
      setIsLogged(false);
    }
  };

  useEffect(() => {
    checkLogin();
  }, [navigate]);

  // Add a new note via API
  const addNote = async () => {
    if (newNote.trim() !== "") {
      try {
        const response = await fetch("http://localhost:9999/api/addNote.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          credentials: "include",
          body: JSON.stringify({ 
            user_id: user.id,
            content: newNote
          }),
        });

        console.log("Response headers:", response.headers);

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.error || `HTTP error: ${response.status}`);
        }

        const data = await response.json();

        // Reset the new note input
        setNewnote("");

        // Reload notes after adding
        fetchNotes();

      } catch (error) {
        console.error("Error adding note", error);
      }
    }
  };

  // Delete a note via API
  const deleteNote = async (id) => {
    if (!window.confirm("Are you sure?")) return;

    try {
      const response = await fetch(`http://localhost:9999/api/deleteNote.php?id=${id}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
        credentials: 'include',
      });

      if (response.ok) {
        // Reload notes after deletion
        fetchNotes();
      }

    } catch (error) {
      console.error("Error deleting note", error);
    }
  };

  const filteredNotes = Array.isArray(notes)
  ? notes.filter((note) =>
      note.content && note.content.toLowerCase().includes(searchTerm.toLowerCase()) || searchTerm === ""
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
            <Note 
              key={note.id}  // Make sure "note.id" is unique
              note={note.content} 
              onDelete={() => deleteNote(note.id)} 
            />
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
