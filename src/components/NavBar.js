import './NavBar.css'

function NavBar() {
    return (
        <div className="navbar">
            <ul className="nav-items">
                <li><input placeholder="search"></input></li>
                <div className="auth">
                    <li><button className='key'>login</button></li>
                    <li><button className='key'>sign in</button></li>
                </div>
                
            </ul>
        </div>
    );
}

export default NavBar;