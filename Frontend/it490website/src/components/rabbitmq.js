const amqp = require('amqplib');

const connectToRabbitMQ = async () => {
  try {
    // Use the ZeroTier IP of your virtual machine
    const connection = await amqp.connect('amqp://10.244.1.4');

    const channel = await connection.createChannel();

    // Additional setup if needed (e.g., declare exchanges, queues, etc.)

    return { connection, channel };
  } catch (error) {
    console.error('Error connecting to RabbitMQ:', error);
    throw error;
  }
};

module.exports = connectToRabbitMQ;
