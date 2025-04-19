<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up - Poole's Pools</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


  <!--Developed by Gage Hutson-->

  <style>
    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      background-color: #414142;
    }

    .signup-page {
      display: flex;
      flex-wrap: wrap;
      min-height: 100vh;
    }

    .signup-left {
      flex: 1;
      background: linear-gradient(135deg, #414142, #414142);
      color: white;
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      border-top-left-radius: 12px;
      border-bottom-left-radius: 12px;
    }

    .signup-left h2 {
      font-weight: bold;
      font-size: 32px;
      margin-bottom: 20px;
      text-align: center;
    }

    .signup-left ul {
      list-style: none;
      padding-left: 0;
      font-size: 18px;
      line-height: 1.8;
    }

    .signup-left ul li::before {
      content: "💧 ";
    }

    .signup-form-container {
      flex: 1;
      background-color: white;
      padding: 40px;
      border: 2px solid #ccc;
      border-radius: 30px;
      position: relative;
      margin: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      padding-top: 60px;
    }


    .signup-form-container h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #000;
      z-index: 1;
      position: relative;
    }

    input, select, textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #cccccc;
      border-radius: 4px;
      background-color: #fff;
      color: #000;
    }

    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #727272;
    }

    .signup-button {
      background: linear-gradient(to right, #1d3ede, #01e6f8);
      color: white;
      padding: 14px;
      border: none;
      width: 100%;
      border-radius: 10px;
      cursor: pointer;
    }

    .signup-button:hover {
      opacity: 0.98;
    }

    .login-link {
      margin-top: 20px;
      text-align: center;
    }

    .login-link a {
      color: #000;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .signup-page {
        flex-direction: column;
      }

      .signup-left, .signup-form-container {
        border-radius: 0;
      }
      .signup-left {
        display: none;
    }
    .signup-form-container {
      flex: 1;
      background-color: white;
      padding: 40px;
      border: 2px solid #ccc;
      border-radius: 30px;
      position: relative;
      margin: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    }

    h2 {
    font-size: 50px;
    margin-bottom: 30px;
    font-weight: 800;
    display: flex;
    background: linear-gradient(270deg, rgb(86, 109, 228), #798d8a, #6a7fe7, #6ca09a); 
    background-size: 400% 400%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: smoothGradient 8s ease infinite;
    margin-bottom: 80px;
    }
    .close-button {
      position: absolute;
      top: 25px;
      right: 40px;
      font-size: 28px;
      font-weight: bold;
      color: #414142;
      text-decoration: none;
      background: none;
      border: none;
      cursor: pointer;
      z-index: 999;
    }

    @keyframes smoothGradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.pool{
  height: 230px;
  width: auto;
  margin-bottom: 60px;
  margin-top: -250px;
}

</style>
</head>
<body>

  <div class="signup-page">
    <div class="signup-left">
      <img class="pool" src="/images/pool3.png" alt="pool">
      <h2 style="font-size: 50px;">Become A </br> Poole's Pool Member</h2>
      <ul>
        <li>Track service appointments</li>
        <li>Set your preferences</li>
        <li>Get seasonal tips</li>
        <li>Receive reminders</li>
        <li>Save pool maintenance history</li>
        <li>Connect with pros</li>
      </ul>
      <a href="about.php">Learn More</a>
    </div>

    <div class="signup-form-container">
      <h2 style="padding-bottom: 10px;">Sign Up:</h2>
      <form action="signingup.php" method="post">
        <h5 style="padding-bottom: 10px; margin-top:45px;" >Personal Info:</h5>
        <input style="border-radius: 15px; margin-top: -4px;" type="text" name="firstname" placeholder="First Name" required>
        <input style="border-radius: 15px;" type="text" name="lastname" placeholder="Last Name" required>
        <input style="border-radius: 15px; margin-top: 10px;" type="email" name="email" placeholder="Email Address" required>
        <input style="border-radius: 15px; margin-top: -3px;" type="tel" name="phone" placeholder="Phone Number" required>
        <h5 style="padding-bottom: 10px; margin-top:15px;" >Account Info:</h5>
        <input style="border-radius: 15px; margin-top: -4px;" type="text" name="username" placeholder="Username" required>
        <div style="position: relative;">
          <input style="border-radius: 15px;" type="password" name="password" id="password" placeholder="Password" required>
          <span class="toggle-password" onclick="toggleVisibility('password')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
        </div>
        
        <div style="position: relative;">
          <input style="border-radius: 15px;" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
          <span class="toggle-password" onclick="toggleVisibility('confirm_password')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
        </div>
        <small id="passwordMatchMessage" style="color: red;"></small>


        <button style="border-radius: 15px; margin-top: 50px;" type="submit" class="signup-button">Sign Up</button>
        </form>

      <div class="login-link">
        <p>Already have an account? <a href="index.php#id01">Log In</a></p>
      </div>
    </div>
    <a href="index.php" class="close-button" title="Back to Home">&times;</a>
  </div>
</body>

</html>

<script>
  function toggleVisibility(id) {
    const input = document.getElementById(id);
    const icon = input.nextElementSibling;
    
    if (input.type === "password") {
      input.type = "text";
      icon.textContent = "🙈";
    } else {
      input.type = "password";
      icon.textContent = "👁️";
    }
  }
  
  const passwordInput = document.querySelector('input[name="password"]');
  const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
  const message = document.getElementById("passwordMatchMessage");
  
  confirmPasswordInput.addEventListener("input", () => {
    if (confirmPasswordInput.value !== passwordInput.value) {
      message.textContent = "Passwords do not match.";
    } else {
      message.textContent = "";
    }
  });
  
  document.querySelector("form").addEventListener("submit", function(e) {
    const form = e.target;
  
    const firstName = form.firstname.value.trim();
    const lastName = form.lastname.value.trim();
    const email = form.email.value.trim();
    const phone = form.phone.value.trim();
    const username = form.username.value.trim();
    const password = form.password.value;
    const confirmPassword = form.confirm_password.value;
  
    if (!firstName || !lastName || !email || !phone || !username || !password || !confirmPassword) {
      alert("Please fill in all required fields.");
      e.preventDefault();
      return;
    }
  
    if (password !== confirmPassword) {
      alert("Passwords do not match.");
      e.preventDefault();
      return;
    }
  });
  </script>
  