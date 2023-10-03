import React, { useState } from 'react';
import { useForm, Controller } from 'react-hook-form';
import { testUsers } from '../test/testlogindata';
import { Link, useNavigate } from 'react-router-dom';

const LoginForm = () => {
  const { control, handleSubmit, formState: { errors } } = useForm({
    defaultValues: {
      username: '',
      password: ''
    }
  });

  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const navigate = useNavigate(); // Add this line

  const onSubmit = (data) => {
    const isValidUser = testUsers.some(
      (user) => user.username === data.username && user.password === data.password
    );

    if (isValidUser) {
      setIsLoggedIn(true);
    } else {
      alert('Invalid username or password');
    }
  };

  // Function to navigate to the registration page
  const goToRegister = () => {
    navigate('/register');
  };

  return (
    <div>
      {isLoggedIn ? (
        <div>
          <p>Welcome, {testUsers.find(user => user.username === control.getValues().username).username}!</p>
          <Link to="/home">Go to Home</Link>
        </div>
      ) : (
        <form onSubmit={handleSubmit(onSubmit)}>
          {/* Username */}
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

          {/* Password */}
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
          <button type="button" onClick={goToRegister}>Go to Register</button> {/* Add this button */}
        </form>
      )}
    </div>
  );
};

export default LoginForm;

