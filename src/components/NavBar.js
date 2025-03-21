import './NavBar.css';

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
                    <li><button className='key'>login</button></li>
                    <li><button className='key'>sign in</button></li>
                </div>
            </ul>
        </div>
    );
}

export default NavBar;
