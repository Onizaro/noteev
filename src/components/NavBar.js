import './NavBar.css'

function NavBar() {
    return (
        <div class="navbar">
            <ul class="nav-items">
                <li><input placeholder="search"></input></li>
                <div class="auth">
                    <li><button>login</button></li>
                    <li><button>sign in</button></li>
                </div>
                
            </ul>
        </div>
    );
}

export default NavBar;