import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import HomeScreen from './pages/homescreen';
import Login from './pages/Login';
import Register from "./pages/register";
import Navbar from './components/Navbar';
import { UserProvider } from '../src/components/StoreUser'; // Import the UserProvider
import ProfilePage from './pages/ProfilePage'; // Import the ProfilePage component

function App() {
  return (
    <Router>
      <UserProvider>
        <div className="App">
          <Navbar />
          <Routes>
            <Route path="/" element={<HomeScreen />} />
            <Route path="/Login" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route path="/ProfilePage/:username" element={<ProfilePage />} />
          </Routes>
        </div>
      </UserProvider>
    </Router>
  );
}

export default App;
