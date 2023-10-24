const express = require('express');
const connectToRabbitMQ = require('./rabbitmq');

const app = express();

app.use(express.json()); // Parse JSON requests

app.post('/login', async (req, res) => {
  const { username, password } = req.body;

  try {
    const { connection, channel } = await connectToRabbitMQ();

    if (!connection || !channel) {
      return res.status(500).json({ error: 'Failed to connect to RabbitMQ' });
    }

    // Additional logic using the RabbitMQ connection and channel
    const message = JSON.stringify({ username, password });
    channel.sendToQueue('login_queue', Buffer.from(message));

    return res.json({ success: true });
  } catch (error) {
    console.error('Error connecting to RabbitMQ:', error);
    return res.status(500).json({ error: 'Cannot connect to messager' });
  }
});

app.listen(3001, () => {
  console.log('Backend server is running on http://10.244.1.6:3001');
});
