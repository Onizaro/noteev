import './App.css';
import NavBar from './components/NavBar';
import PlusIcon from './icons/plus.svg'

function App() {
  const isempty = true;
  let content = isempty ? <h1>You don't have any tasks yet.</h1> : <h1>Your tasks</h1>;
  console.log(content);
  return (
    <div className="App">
      <NavBar></NavBar>
      <header>
        {content}
        <div className='add-note'>
          <button type='submit'>
          <img className="plus-icon" src={PlusIcon} alt="PlusIcon"></img>
            Add
          </button>
        </div>
      </header>
      
      
    </div>
  );
}

export default App;
