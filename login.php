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
    $prepared = $db->prepare("SELECT * from users WHERE (username = :username);");
    $prepared->execute(['username' => $entered_username]);
    $result = $prepared->fetchAll();
    if(count($result) == 0) $login_failed_message = "Login failed.";
    else {
      $hashed_password = $result[0]['password'];
      if(password_verify($entered_password, $hashed_password)) {
        $_SESSION['logged_in'] = 1;
        $_SESSION['user_id'] = $result[0]['user_id'];
        $_SESSION['username'] = $result[0]['username'];
        $_SESSION['fname'] = $result[0]['fname'];
        $_SESSION['lname'] = $result[0]['lname'];
        $_SESSION['type'] = $result[0]['type'];
        $_SESSION['filepath'] = $result[0]['filepath'];
        $_SESSION['dob'] = $result[0]['dob'];
        header("Location: dashboard.php");
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
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/style.css">
  <script src="./scripts/redirect.js" defer></script>
  <script src="./scripts/profile.js" defer></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous" defer></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous" defer></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous" defer></script>
  <title>Log into JAM</title>
</head>

<body>
  <!-- Navbar -->
  <nav>
    <a class="active" href="./index.php">Home</a>
    <div class="right">
      <!-- Links -->
      <a href="./dashboard.php">Dashboard</a>
      <button id="profile-icon-button" onclick="toggleProfile()">
        <img class="profile-icon" src="Resources/assets/profile.png" alt="User Profile Picture"/>
      </button>
    </div>
  </nav>

  <!-- Profile section -->
  <div id="profile-menu" style="display: none;">
    <div id="profile-overview">
      <img class="profile-icon" src="Resources/assets/profile.png" alt="User Profile Picture"/>
      <p><?php echo $_SESSION['username'] ?>'s Profile</p>
    </div>
    <div>
      <?php
        if(!empty($_SESSION['fname']) || !empty($_SESSION['lname'])) {
          echo "<p>Name: " . $_SESSION['fname'] . " " . $_SESSION['lname'] . "</p>";
        }
        if($_SESSION['type'] == 1) echo "<p>User Type: Premium</p>";
        else echo "<p>User Type: Standard</p>";
        if($_SESSION['dob'] != "0000-00-00") echo "<p>Date of Birth: " . $_SESSION['dob'] . "</p>";
      ?>
      <form id="logout-form" method="post">
        <input type="submit" id="logout" value="Logout"/>
      </form>
    </div>
  </div>

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
      <a href="register.php"><b>Register →</b></a>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p class="copyright text-white">© JAM 2021</p>
  </footer>
</body>
</html>