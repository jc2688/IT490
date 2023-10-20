import React from 'react';
import { useForm, Controller } from 'react-hook-form';
import { testUsers } from '../test/testlogindata';
import { Link, useNavigate } from 'react-router-dom';
import { useUser } from '../components/StoreUser';
import { connectToRabbitMQ } from './rabbitmq'; // Import the RabbitMQ connection

const LoginForm = () => {
  const { control, handleSubmit, formState: { errors } } = useForm({
    defaultValues: {
      username: '',
      password: ''
    }
  });

  const navigate = useNavigate();

  const onSubmit = async (data) => {
    const user = testUsers.find(user => user.username === data.username);

    if (!user || user.password !== data.password) {
      alert('Invalid username or password');
      return;
    }

    localStorage.setItem('isLoggedIn', 'true');
    localStorage.setItem('username', data.username);

    // Connect to RabbitMQ
    const { channel } = await connectToRabbitMQ();

    // Publish the message
    const message = JSON.stringify(data);
    channel.sendToQueue('login_queue', Buffer.from(message));

    navigate('/');
  };

  const username = localStorage.getItem('username');

  return (
    <div>
      {isLoggedIn ? (
        <div>
          <p>Welcome, {username}!</p>
          <Link to="/">Go to Home</Link>
        </div>
      ) : (
        <form onSubmit={handleSubmit(onSubmit)}>
          <div>
            <label>Username:</label>
            <Controller
              name="username"
              control={control}
              defaultValue=""
              rules={{ required: true }}
              render={({ field }) => <input {...field} type="text" />}
            />
            {errors.username && (
              <div className="error">Username is required.</div>
            )}
          </div>

          <div>
            <label>Password:</label>
            <Controller
              name="password"
              control={control}
              defaultValue=""
              rules={{ required: true }}
              render={({ field }) => <input {...field} type="password" />}
            />
            {errors.password && (
              <div className="error">Password is required.</div>
            )}
          </div>

          <button type="submit">Login</button>
          <button type="button" onClick={goToRegister}>Go to Register</button>
        </form>
      )}
    </div>
  );
};

export default LoginForm;
