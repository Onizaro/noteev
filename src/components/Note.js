import "./Note.css";
import "./NavBar.css"

function Note({ note, onDelete }) {
  const copyToClipboard = () => {
    navigator.clipboard.writeText(note).then(() => {
      alert("Texte copié !");
    });
  };
  return (
    <li className="note">
      <span title="click to copy" onClick={copyToClipboard}>{note}</span>
      <button className="key" onClick={onDelete}>SUPPR</button>
    </li>
  );
}

export default Note;
