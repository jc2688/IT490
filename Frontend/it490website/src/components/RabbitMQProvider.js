// RabbitMQProvider.jsx

import React, { useState, useEffect } from 'react';
import connectToRabbitMQ from './rabbitmq';

const RabbitMQProvider = ({ children }) => {
  const [connection, setConnection] = useState(null);
  const [channel, setChannel] = useState(null);

  useEffect(() => {
    connectToRabbitMQ().then(({ connection, channel }) => {
      setConnection(connection);
      setChannel(channel);
    });

    return () => {
      if (channel) channel.close();
      if (connection) connection.close();
    };
  }, []);

  return <>{children}</>;
};

export default RabbitMQProvider;
