import React, { useState } from 'react';
import { useForm, Controller } from 'react-hook-form';
import {testUsers} from '../test/testlogindata';
import { Link } from 'react-router-dom'; // Add this line

const RegistrationForm = () => {
    const { control, handleSubmit, watch, formState: { errors }, } = useForm({
        defaultValues: {
          username: '',
          email: '',
          password: '',
          confirmPassword: ''
        }
      });
  const [isRegistered, setIsRegistered] = useState(false);
  const [errorMessages, setErrorMessages] = useState({
    username: '',
    email: '',
    password: '',
    confirmPassword: '',
  });

  const handleRegistration = async (data) => {
    const userWithSameUsername = testUsers.find(user => user.username === data.username);
    const userWithSameEmail = testUsers.find(user => user.email === data.email);
  
    if (userWithSameUsername) {
      setErrorMessages(prevState => ({ ...prevState, username: 'User with this username already exists.' }));
    } else {
      setErrorMessages(prevState => ({ ...prevState, username: '' }));
    }
  
    if (userWithSameEmail) {
      setErrorMessages(prevState => ({ ...prevState, email: 'User with this email address already exists.' }));
    } else {
      setErrorMessages(prevState => ({ ...prevState, email: '' }));
    }
  
    if (!userWithSameUsername && !userWithSameEmail) {
      // Continue with registration logic
      try {
        setIsRegistered(true);
      } catch (error) {
        console.error('Error sending registration data to RabbitMQ:', error);
      }
    }
  };
  

  const onSubmit = async (data) => {
    console.log('Form data:', data);
    let isValid = true;
  
    if (data.password.length < 8) {
      setErrorMessages(prevState => ({ ...prevState, password: 'Password must be at least 8 characters long.' }));
      isValid = false;
    } else {
      setErrorMessages(prevState => ({ ...prevState, password: '' }));
    }
  
    if (isValid) {
      console.log('Form is valid. Submitting...');
      handleRegistration(data);
    }
  };

  return (
    <div>
      {isRegistered ? (
        <div>
          <p>Account has been successfully registered.</p>
          <Link to="/Login">Back to Login</Link> {/* Add this line */}
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
              rules={{ required: true, minLength: 8 }}
              render={({ field }) => <input {...field} type="text" />}
            />
            {errors.username && errors.username.type === 'required' && (
              <div className="error">Username is required.</div>
            )}
            {errors.username && errors.username.type === 'minLength' && (
              <div className="error">Username must be at least 8 characters.</div>
            )}
            {errorMessages.username && (
              <div className="error">{errorMessages.username}</div>
            )}
          </div>

          {/* Email */}
          <div>
            <label>Email:</label>
            <Controller
              name="email"
              control={control}
              defaultValue=""
              rules={{ required: true, pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/ }}
              render={({ field }) => <input {...field} type="email" />}
            />
            {errors.email && errors.email.type === 'required' && (
              <div className="error">Email is required.</div>
            )}
            {errors.email && errors.email.type === 'pattern' && (
              <div className="error">Invalid email address.</div>
            )}
            {errorMessages.email && (
              <div className="error">{errorMessages.email}</div>
            )}
          </div>

                {/* Password */}
                <div>
                    <label>Password:</label>
                    <Controller
                        name="password"
                        control={control}
                        defaultValue=""
                        rules={{
                        required: true,
                        validate: (value) => {
                            return (
                            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value) ||
                            'Password must have at least one uppercase letter, one lowercase letter, one number, and one special character.'
                            );
                        }
                        }}
                        render={({ field }) => <input {...field} type="password" />}
                    />
                    {errors.password && (
                        <div className="error">{errors.password.message}</div>
                    )}
                    {errorMessages.password && (
                        <div className="error">{errorMessages.password}</div>
                    )}
                </div>

     
    
          {/* Confirm Password */}
          <div>
            <label>Confirm Password:</label>
            <Controller
              name="confirmPassword"
              control={control}
              defaultValue=""
              rules={{
                validate: value => value === watch('password')
              }}
              render={({ field }) => <input {...field} type="password" />}
            />
            {errors.confirmPassword && (
              <div className="error">Passwords do not match.</div>
            )}
          </div>

          <button type="submit">Register</button>
        </form>
      )}
    </div>
  );
};

export default RegistrationForm;