// server/index.js

const express = require('express');
const cors = require('cors');

const app = express();

app.use(cors());
app.use(express.json()); // Parse JSON requests

app.post('/homescreen', async (req, res) => {
  try {
    // Simulating a successful connection to RabbitMQ
    // This is a placeholder for your RabbitMQ logic
    // Replace this with your actual RabbitMQ logic
    const connection = true;
    const channel = true;

    if (!connection || !channel) {
      return res.status(500).json({ error: 'Failed to connect to RabbitMQ' });
    }

    const { type, message } = req.body;

    // Simulated RabbitMQ logic
    const dbQueueMessage = JSON.stringify({ type, message });

    return res.json({ success: true, dbQueueMessage });
  } catch (error) {
    console.error('Error connecting to RabbitMQ:', error);
    return res.status(500).json({ error: 'Cannot connect to messenger' });
  }
});

app.listen(3001, () => {
  console.log('Backend server is running on http://localhost:3001');
});
