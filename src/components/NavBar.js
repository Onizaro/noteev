import './NavBar.css'

function NavBar() {
    return (
        <div className="navbar">
            <ul className="nav-items">
                <li><input placeholder="search"></input></li>
                <div className="auth">
                    <li><button>login</button></li>
                    <li><button>sign in</button></li>
                </div>
                
            </ul>
        </div>
    );
}

export default NavBar;