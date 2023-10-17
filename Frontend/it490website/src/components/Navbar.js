import React from 'react';
import { Link } from 'react-router-dom';
import { useUser } from '../components/StoreUser'; // Import the useUser hook

const Navbar = () => {
  const { isLoggedIn, username } = useUser();

  return (
    <nav>
      <ul>
        <li>
          <Link to="/">Home</Link>
        </li>
        <li>
          {isLoggedIn ? (
            <Link to={`/ProfilePage/${username}`}>Profile</Link>
          ) : (
            <Link to="/login">Login</Link>
          )}
        </li>
      </ul>
    </nav>
  );
};

export default Navbar;

