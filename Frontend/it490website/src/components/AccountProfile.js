const AccountProfile = ({ username }) => {
    const handleLogout = () => {
      localStorage.removeItem('isLoggedIn');
      localStorage.removeItem('username');
      navigate('/Login');
    };
  
    return (
      <div>
        <h1>Account Profile</h1>
        <p>Welcome, {username}!</p>
        <button onClick={handleLogout}>Logout</button>
      </div>
    );
  };
  
  export default AccountProfile;
