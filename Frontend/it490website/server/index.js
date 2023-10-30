const express = require('express');
const cors = require('cors');

const app = express();

app.use(cors());
app.use(express.json());

app.post('/api/homescreen', async (req, res) => { // Adjusted route
  try {
    // Simulating a successful connection to RabbitMQ
    const connection = true;
    const channel = true;

    if (!connection || !channel) {
      return res.status(500).json({ error: 'Failed to connect to RabbitMQ' });
    }

    const { type, message } = req.body;

    const dbQueueMessage = JSON.stringify({ type, message });

    return res.json({ success: true, dbQueueMessage });
  } catch (error) {
    console.error('Error connecting to RabbitMQ:', error);
    return res.status(500).json({ error: 'Cannot connect to messenger' });
  }
});

app.get('/', (req, res) => {
  res.send('Welcome to the homepage');
});

app.listen(3001, () => {
  console.log('Backend server is running on http://10.244.1.6:3001');
});
