import { useState } from "react";
import { Link } from "react-router-dom";
import "./SignIn.css"

export default function Login() {
  const [identifier, setIdentifier] = useState(""); // Peut être username ou email
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage(null); // Réinitialiser le message avant l'envoi

    try {
      const response = await fetch("http://localhost:4665/login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ identifier, password }),
      });

      const data = await response.json();

      if (response.ok) {
        setMessage({ type: "success", text: "Connexion successful!" });
      } else {
        setMessage({ type: "error", text: data.error || "Invalid credentials" });
      }
    } catch (error) {
      setMessage({ type: "error", text: "Network error. Please try again." });
    }
  };

  return (
    <div className="body">
      <h1>Login</h1>
      
      {message && (
        <div className={`mb-2 p-2 rounded ${message.type === "success" ? "bg-green-500 text-white" : "bg-red-500 text-white"}`}>
          {message.text}
        </div>
      )}

      <form className="form" onSubmit={handleSubmit}>
        <input 
          type="text" 
          placeholder="Username or Email" 
          value={identifier} 
          onChange={(e) => setIdentifier(e.target.value)} 
        />
        <input 
          type="password" 
          placeholder="Password" 
          value={password} 
          onChange={(e) => setPassword(e.target.value)} 
        />
        <button className="key" type="submit">Log In</button>
      </form>
      <div className="footer">
        <Link to="/"><button className="key">Home</button></Link>
        <Link to="/sign-in"><button className="key">Sign In</button></Link>
      </div>
    </div>
  );
}
