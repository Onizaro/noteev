import "./Note.css";

function Note({ note, onDelete }) {
  return (
    <li className="note">
      <span>{note}</span>
      <button className="delete-btn" onClick={onDelete}>❌</button>
    </li>
  );
}

export default Note;
