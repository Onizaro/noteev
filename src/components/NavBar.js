import './NavBar.css';
import {Link} from 'react-router-dom'

function NavBar({ searchTerm, setSearchTerm, isLogged, setIsLogged, fetchNotes  }) {
    const notLogged = (
        <div className="auth">
            <li><Link to="/login"><button className='key' >login</button></Link></li>
            <li><Link to="/sign-in"><button className='key'>sign in</button></Link></li>
        </div>
    )

    const handleLogout = async () => {
        await fetch("http://localhost:4665/logout.php", { credentials: "include" });
        setIsLogged(false);
        fetchNotes();
      };
      

    const logged = (
        <div className="auth">
            <li><button onClick={handleLogout}  className='key'>logout</button></li>
        </div>
    )

    const buttons =() => {
        if (isLogged==null){
            return (
                <div className="auth">
                    
                </div>
            );
        }
        else {
            return isLogged ? logged: notLogged
        }
    };

    
    return (
        <div className="navbar">
            <ul className="nav-items">
                <li>
                    <input 
                        className='searchbar'
                        placeholder="search"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                    />
                </li>
                {buttons()}
                
            </ul>
        </div>
    );
}

export default NavBar;
