import React from 'react';
import { Link, Outlet } from 'react-router-dom';

const Navbar = () => {
  return (
    <nav>
      <ul>
        <li>
          <Link to="/">Home</Link>
        </li>
        <li>
          <Link to="/Login">Login</Link>
        </li>
      </ul>
      <hr />
      <Outlet /> {/* This is where nested routes will be rendered */}
    </nav>
  );
};

export default Navbar;