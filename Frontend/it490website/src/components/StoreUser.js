import React, { createContext, useContext, useState } from 'react';

const UserContext = createContext();

export const useUser = () => {
  const context = useContext(UserContext);
  if (!context) {
    throw new Error('useUser must be used within a UserProvider');
  }
  return context;
};

export const UserProvider = ({ children }) => {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [username, setUsername] = useState('');

  const loginUser = (username) => {
    setIsLoggedIn(true);
    setUsername(username);
  };

  const logoutUser = () => {
    setIsLoggedIn(false);
    setUsername('');
  };

  return (
    <UserContext.Provider value={{ isLoggedIn, username, loginUser, logoutUser }}>
      {children}
    </UserContext.Provider>
  );
};