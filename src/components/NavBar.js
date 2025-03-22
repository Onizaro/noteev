import './NavBar.css';
import {Link} from 'react-router-dom'

function NavBar({ searchTerm, setSearchTerm }) {
    return (
        <div className="navbar">
            <ul className="nav-items">
                <li>
                    <input 
                        placeholder="search"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                    />
                </li>
                <div className="auth">
                    <li><Link to="/login"><button className='key' href="login">login</button></Link></li>
                    <li><Link to="/sign-in"><button className='key'>sign in</button></Link></li>
                </div>
            </ul>
        </div>
    );
}

export default NavBar;
