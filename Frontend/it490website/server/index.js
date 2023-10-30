const express = require('express');
const cors = require('cors');
const amqp = require('amqplib/callback_api'); // Added amqp import

const app = express();

app.use(cors());
app.use(express.json());

const connectToRabbitMQ = async () => {
  try {
    const connection = await amqp.connect('amqp://10.244.1.4'); // Connect to RabbitMQ server
    const channel = await connection.createChannel(); // Create a channel
    
    const queue = 'dbQueue'; // Specify the queue name

    // Assert the queue, this makes sure the queue exists
    channel.assertQueue(queue, { durable: false });
    
    return channel; // Return the channel for use in sending messages
  } catch (error) {
    console.error('Error connecting to RabbitMQ:', error);
    return null;
  }
};

app.post('/homescreen', async (req, res) => {
  try {
    const { type, message } = req.body;

    const dbQueueMessage = JSON.stringify({ type, message });

    const channel = await connectToRabbitMQ();
    if (channel) {
      channel.sendToQueue('dbQueue', Buffer.from(dbQueueMessage)); // Send message to RabbitMQ
    }

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
