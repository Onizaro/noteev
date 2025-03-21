import { useState } from "react";
import "./App.css";
import './components/NavBar.css'
import NavBar from "./components/NavBar";
import PlusIcon from "./icons/plus.svg";
import Note from "./components/Note";

function App() {
  const [notes, setNotes] = useState([]);
  const [newNote, setNewnote] = useState("");

  const addNote = () => {
    if (newNote.trim() !== "") {
      setNotes([...notes, newNote]);
      setNewnote(""); 
    }
  };

  const deleteNote = (index) => {
    setNotes(notes.filter((_, i) => i !== index));
  };

  return (
    <div className="App">
      <NavBar />

      <header>
        <input
            type="text"
            class="write"
            placeholder="Write here..."
            value={newNote}
            onChange={(e) => setNewnote(e.target.value)}
          />

        <div className="add-note">
          <button className="key" type="button" onClick={addNote}>
            <img className="plus-icon" src={PlusIcon} alt="Add Note" />
            Add
          </button>
        </div>
        
      </header>
      

      <section className="notes">
        {notes.length > 0 && <h1>My notes</h1>}
        <ul className="note-list">
          {notes.map((note, index) => (
            <Note key={index} note={note} onDelete={() => deleteNote(index)}/>
          ))}
        </ul>
      </section>
    </div>
  );
}

export default App;
