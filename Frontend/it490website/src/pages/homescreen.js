import React, { useState } from 'react';

const HomeScreen = () => {
  const [response, setResponse] = useState(null);

  const sendToServer = async () => {
    try {
      const response = await fetch('http://localhost:3001/sendMessage', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          type: 'rabbitTest',
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
      alert('Cannot connect to server');
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
          <pre>{JSON.stringify(response, null, 2)}</pre>
        </div>
      )}
    </div>
  );
};

export default HomeScreen;
