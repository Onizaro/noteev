import "./Note.css";
import "./NavBar.css"

function Note({ note, onDelete }) {
  return (
    <li className="note">
      <span>{note}</span>
      <button className="key" onClick={onDelete}>SUPPR</button>
    </li>
  );
}

export default Note;
