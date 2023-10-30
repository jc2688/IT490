// src/pages/homescreen.js

import React, { useState } from 'react';

const HomeScreen = () => {
  const [response, setResponse] = useState(null);

  const sendToServer = async () => {
    try {
      const response = await fetch('http://10.244.1.6:7007/homescreen', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          type: 'testRabbit', // Updated type
          message: 'Hello World'
        })
      });

      if (response.ok) {
        const responseData = await response.json();
        setResponse(responseData);
      } else {
        throw new Error('Error sending data to server');
      }
    } catch (error) {
      console.error('Error connecting to server:', error);
      setResponse({ error: 'Cannot connect to server' });
    }
  };

  return (
    <div>
      <h1>Welcome to the Home Screen</h1>
      <p>This is the main content of the home screen.</p>
      <button onClick={sendToServer}>Send Message to Server</button>
      {response && (
        <div>
          <h2>Response from Server:</h2>
          {response.error ? (
            <div style={{ color: 'red' }}>{response.error}</div>
          ) : (
            <pre>{JSON.stringify(response, null, 2)}</pre>
          )}
        </div>
      )}
    </div>
  );
};

export default HomeScreen;
