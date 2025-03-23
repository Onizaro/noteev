import { useState } from "react";
import "./Note.css";
import "./NavBar.css";

function Note({ note, onDelete }) {
  const [copied, setCopied] = useState(false);
  const [isModalOpen, setIsModalOpen] = useState(false);

  const copyToClipboard = () => {
    navigator.clipboard.writeText(note).then(() => {
      setCopied(true);
      setTimeout(() => {
        setCopied(false);
      }, 3000); // Remettre la note après 3 secondes
    });
  };

  const openModal = () => setIsModalOpen(true);
  const closeModal = () => setIsModalOpen(false);

  return (
    <div>
      <li className="note" onClick={openModal} title="click for more">
        <span >
          {note}
        </span>
      </li>

      {isModalOpen && (
        <div className="modal-overlay" >
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <div className="keys">
              <button className="key echap" onClick={closeModal}>ECHAP</button>
              <button className="key suppr" onClick={onDelete}>SUPPR</button>
            </div>
            
            <span className="modal-title">Note Details</span>
            <span className="modal-text"title="click to copy" onClick={(e) => {e.stopPropagation(); copyToClipboard();}}>
              {copied ? "Text copied!" : note}
            </span>
            
          </div>
        </div>
      )}
    </div>
  );
}

export default Note;
