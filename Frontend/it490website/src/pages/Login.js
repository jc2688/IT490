import React from 'react';
import { useForm, Controller } from 'react-hook-form';
import { testUsers } from '../test/testlogindata';
import { Link, useNavigate } from 'react-router-dom';
import { useUser } from '../components/StoreUser';

const LoginForm = () => {
  const { control, handleSubmit, formState: { errors } } = useForm({
    defaultValues: {
      username: '',
      password: ''
    }
  });

  const navigate = useNavigate();
  const { isLoggedIn, loginUser } = useUser(); // Use the useUser hook

  const onSubmit = async (data) => {
    const user = testUsers.find(user => user.username === data.username);

    if (!user || user.password !== data.password) {
      alert('Invalid username or password');
      return;
    }

    // Call the loginUser function from useUser to set isLoggedIn to true
    loginUser(data.username);

    localStorage.setItem('isLoggedIn', 'true');
    localStorage.setItem('username', data.username);

    try {
      const response = await fetch('http://10.244.1.6:7007/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          username: data.username,
          password: data.password, // Send the password (not hashed) to the server
        }),
      });

      if (!response.ok) {
        throw new Error('Error sending data to server');
      }
    } catch (error) {
      console.error('Error connecting to server:', error);
      alert('Cannot connect to server');
    }

    navigate('/');
  };

  const username = localStorage.getItem('username');

  const goToRegister = () => {
    navigate('/register');
  };

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
