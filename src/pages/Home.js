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

  const addNote = () => {
    if (newNote.trim() !== "") {
      setNotes([...notes, newNote]);
      setNewnote(""); 
    }
  };

  const deleteNote = (index) => {
    if (window.confirm("Are you sure?")) {
      setNotes(notes.filter((_, i) => i !== index));
    }
  };

  const filteredNotes = notes.filter(note => 
    note.toLowerCase().includes(searchTerm.toLowerCase()) || searchTerm === ""
  );

  const handleLogout = async () => {
    await fetch("http://localhost:4665/logout.php", { credentials: "include" });
    window.location.href = "/login"; // Redirige vers la page de connexion
  };


  // jwt part
  const [user, setUser] = useState(null);
  const navigate = useNavigate();
  
  useEffect(() => {
    const checkLogin = async () => {
      const response = await fetch("http://localhost:4665/checkAuth.php", { credentials: "include" });
      const data = await response.json();
      if (response.ok) {
        setUser(data.user);
        setIsLogged(true);
      }
      else {
        setIsLogged(false);
      }
    };

    checkLogin();
  }, []);
  const [isLogged, setIsLogged] = useState(user !== null);
  
  return (
    <div className="App">
      <NavBar searchTerm={searchTerm} setSearchTerm={setSearchTerm} isLogged={isLogged} setIsLogged={setIsLogged}/>

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
          {filteredNotes.map((note, index) => (
            <Note key={index} note={note} onDelete={() => deleteNote(index)}/>
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
}

export default Home;
