const amqp = require('amqplib/callback_api');

const connectToRabbitMQ = async () => {
  try {
    const connection = await amqp.connect('amqp://localhost');

    if (!connection) {
      throw new Error('Failed to establish connection to RabbitMQ');
    }

    const channel = await connection.createChannel();

    return { connection, channel };
  } catch (error) {
    console.error('Error connecting to RabbitMQ:', error);
    return null;
  }
};

module.exports = connectToRabbitMQ;