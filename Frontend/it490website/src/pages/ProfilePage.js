import React from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useUser } from '../components/StoreUser';

const ProfilePage = () => {
  const { username } = useParams();
  const navigate = useNavigate(); 
  const { logoutUser } = useUser(); // Get the logoutUser function from your context

  const handleLogout = () => {
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('username');
    logoutUser(); // Call the logout function from your context
    navigate('/Login');
  };

  return (
    <div>
      <h1>Account Profile</h1>
      <p>Welcome, {username}!</p>
      <button onClick={handleLogout}>Logout</button>
    </div>
  );
};

export default ProfilePage;


