<?php
session_start();
include_once('config.php');

try {
  $db = new PDO('mysql:host=localhost;dbname=JAM', $db_username, $db_password);
}
catch(PDOException $e) {
  echo "Failed to connect to JAM database: " . $e->getMessage() . "<br>";
  exit();
}

$login_failed_message = "";

if(isset($_POST['login-button']) && $_POST['login-button'] == "Login") {
  $entered_username = $_POST['input-username'];
  $entered_password = $_POST['input-password'];

  if(empty($entered_username)) $login_failed_message = "Please enter a username.";
  else if(empty($entered_password)) $login_failed_message = "Please enter a password.";
  else {
    $prepared = $db->prepare("SELECT password from users WHERE (username = :username);");
    $prepared->execute(['username' => $entered_username]);
    $result = $prepared->fetchAll();
    if(count($result) == 0) $login_failed_message = "Login failed.";
    else {
      $hashed_password = $result[0]["password"];
      if(password_verify($entered_password, $hashed_password)) {
        $_SESSION['logged_in'] = 1;
        $_SESSION['user_id'] = $result[0]['user_id'];
        $_SESSION['username'] = $entered_username;
        header("Location: dashboard.html");
        exit();
      }
      else $login_failed_message = "Login failed.";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="styles/style.css">
  <!-- Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css?family=Roboto+Mono&subset=cyrillic" rel="stylesheet">
  <title>Log into JAM</title>
</head>

<body>
  <!-- Navbar -->
  <nav>
    <a class="active" href="./index.html">Home</a>
    <div class="right">
      <a href="./dashboard.html">Dashboard</a>
      <a href="./dashboard.html">Test</a>
    </div>
  </nav>

  <div class="login">
    <div>
      <!-- JAM Logo -->
      <img src="Resources/assets/logo.png" alt="JAM Logo" width="250" height="250">

      <h1>Log into your Account</h1>
      <p>
        <?php
          echo $login_failed_message . "<br>";
        ?>
      </p>

      <!-- Login Form -->
      <form class="login-form" method="post" action="login.php">
        <!-- Input Email Address -->
        <div>
          <label for="input-username" class="required">Username</label>
          <input type="text" name="input-username" id="input-username" required/>
        </div>
        <!-- Input Password -->
        <div>
          <label for="input-password" class="required">Password</label>
          <input type="password" name="input-password" id="input-password" required/>
        </div>
        <!-- Login Button -->
        <input type="submit" name="login-button" id="login-button" value="Login"/>
      </form>

      <!-- Register Prompt -->
      <h4>Don't have an account?</h4>
      <a href="register.html"><b>Register →</b></a>
    </div>
  </div>
</body>
</html>