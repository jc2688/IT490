const express = require('express');
const cors = require('cors');
const amqp = require('amqplib'); // Changed import to use Promises

const app = express();

app.use(cors());
app.use(express.json());

const connectToRabbitMQ = () => {
  return new Promise((resolve, reject) => {
    try {
      amqp.connect('amqp://jdiaz:rabbit.pwd@10.244.1.4', (error, connection) => {
        if (error) {
          console.error('Error connecting to RabbitMQ:', error);
          reject(error);
          return;
        }

        connection.createChannel((channelError, channel) => {
          if (channelError) {
            console.error('Error creating channel:', channelError);
            reject(channelError);
            return;
          }

          const queue = 'dbQueue';
          channel.assertQueue(queue, { durable: false });
          resolve(channel);
        });
      });
    } catch (error) {
      console.error('Error connecting to RabbitMQ:', error);
      reject(error);
    }
  });
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

