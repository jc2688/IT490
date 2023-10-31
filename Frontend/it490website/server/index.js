const express = require('express');
const cors = require('cors');
const amqp = require('amqplib'); // Changed import to use Promises

const app = express();

app.use(cors());
app.use(express.json());

const connectToRabbitMQ = () => {
  return new Promise((resolve, reject) => {
    try {
      // Read the configuration from [database] section
      const connectionDetails = {
        BROKER_HOST: '10.244.1.4',
        BROKER_PORT: 5672,
        USER: 'jdiaz',
        PASSWORD: 'rabbit.pwd',
        VHOST: 'main',
        EXCHANGE: 'amq.direct',
        QUEUE: 'dbQueue',
        EXCHANGE_TYPE: 'direct',
        AUTO_DELETE: true
      };

      const connectionUrl = `amqp://${connectionDetails.USER}:${connectionDetails.PASSWORD}@${connectionDetails.BROKER_HOST}:${connectionDetails.BROKER_PORT}`;
      amqp.connect(connectionUrl)
        .then((connection) => {
          return connection.createChannel();
        })
        .then((channel) => {
          const queue = connectionDetails.QUEUE;
          channel.assertQueue(queue, { durable: false });
          resolve(channel);
        })
        .catch((error) => {
          console.error('Error connecting to RabbitMQ:', error);
          reject(error);
        });
    } catch (error) {
      console.error('Error connecting to RabbitMQ:', error);
      reject(error);
    }
  });
};

// Rest of your code remains the same...







app.post('/homescreen', async (req, res) => {
  try {
    console.log('Received request:', req.body);

    const { type, message } = req.body;

    const dbQueueMessage = JSON.stringify({ type, message });

    const channel = await connectToRabbitMQ();
    if (channel) {
      channel.sendToQueue('dbQueue', Buffer.from(dbQueueMessage)); // Send message to RabbitMQ
    }

    // Send an immediate response to the client
    res.json({ success: true, message: 'Request received, processing in progress' });

  } catch (error) {
    console.error('Error connecting to RabbitMQ:', error);
    return res.status(500).json({ error: 'Cannot connect to messenger' });
  }
});

app.get('/testrabbit', async (req, res) => {
  try {
    const channel = await connectToRabbitMQ();
    if (channel) {
      console.log('Connected to RabbitMQ');
      return res.json({ success: true, message: 'Connected to RabbitMQ' });
    }
    console.log('Failed to connect to RabbitMQ');
    return res.status(500).json({ error: 'Failed to connect to RabbitMQ' });
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
