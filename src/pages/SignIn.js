import { useState } from "react";
import { Link } from "react-router-dom";
import "./SignIn.css"

export default function Register() {
  const [username, setUsername] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState(null);

  const validateEmail = (email) => /\S+@\S+\.\S+/.test(email);
  const validatePassword = (password) => password.length >= 6;

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage(null);

    if (!username || !email || !password) {
      setMessage({ type: "error", text: "All fields are required" });
      return;
    }

    if (!validateEmail(email)) {
      setMessage({ type: "error", text: "Invalid email format" });
      return;
    }

    if (!validatePassword(password)) {
      setMessage({ type: "error", text: "Password must be at least 6 characters long" });
      return;
    }

    try {
      const response = await fetch("http://localhost:4665/register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, email, password }),
      });

      const data = await response.json();

      if (response.ok) {
        setMessage({ type: "success", text: "User registered successfully!" });
      } else {
        setMessage({ type: "error", text: data.error || "Registration failed" });
      }
    } catch (error) {
      setMessage({ type: "error", text: "Network error. Please try again." });
    }
  };

  return (
    <div className="body">
      <h1>Sign In Page</h1>

      {message && (
        <div className={`${message.type === "success" ? "red" : "green"}`}>
          {message.text}
        </div>
      )}

      <form className="form" onSubmit={handleSubmit}>
        <input 
          type="text" 
          placeholder="Username" 
          value={username} 
          onChange={(e) => setUsername(e.target.value)} 
        />
        <input 
          type="email" 
          placeholder="Email" 
          value={email} 
          onChange={(e) => setEmail(e.target.value)} 
        />
        <input 
          type="password" 
          placeholder="Password " 
          value={password} 
          onChange={(e) => setPassword(e.target.value)} 
        />
        <button className="key" type="submit">Sign In</button>
      </form>

      <div className="footer">
        <Link to="/"><button className="key">Home</button></Link>
        <Link to="/login"><button className="key">Log In</button></Link>
      </div>
    </div>
  );
}
