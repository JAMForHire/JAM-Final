<?php
include_once('config.php');

try {
  $db = new PDO('mysql:host=localhost;dbname=JAM', $db_username, $db_password);
}
catch(PDOException $e) {
  echo "Failed to connect to JAM database: " . $e->getMessage() . "<br>";
  exit();
}

$register_failed_message = "";

if(isset($_POST['register-button']) && $_POST['register-button'] == "Register") {
  $fname = $_POST['input-first-name'];
  $lname = $_POST['input-last-name'];
  $username = $_POST['input-username'];
  $dob = $_POST['input-dob'];
  $pfp = $_POST['upload-pfp'];
  $password = $_POST['input-password'];
  $confirmed_password = $_POST['input-confirm-password'];

  $prepared = $db->prepare("SELECT * from users WHERE (username = :username);");
  $prepared->execute(['username' => $username]);
  $result = $prepared->fetchAll();

  if(empty($username)) $register_failed_message = "Please enter a username.";
  else if(empty($password)) $register_failed_message = "Please enter a password.";
  else if($password != $confirmed_password) $register_failed_message = "Passwords do not match.";
  else if(count($result) > 0) $register_failed_message = "User is already registered.";
  else {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $prepared = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password);");
    $prepared->execute(['username' => $username, 'password' => $hashed_password]);
    $user_id = $db->lastInsertId();

    if(!empty($fname)) {
      $prepared = $db->prepare("UPDATE users SET fname = :fname WHERE user_id = :user_id;");
      $prepared->execute(['fname' => $fname, 'user_id' => $user_id]);
    }
    if(!empty($lname)) {
      $prepared = $db->prepare("UPDATE users SET lname = :lname WHERE user_id = :user_id;");
      $prepared->execute(['lname' => $lname, 'user_id' => $user_id]);
    }
    if(!empty($dob)) {
      $prepared = $db->prepare("UPDATE users SET dob = :dob WHERE user_id = :user_id;");
      $prepared->execute(['dob' => $dob, 'user_id' => $user_id]);
    }

    header("Location: login.php");
    exit();
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
  <title>Create a JAM Account</title>
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

  <div class="register">
    <div>
      <!-- JAM Logo -->
      <img src="Resources/assets/logo.png" alt="JAM Logo" width="250" height="250">

      <h1>Create an Account</h1>
      <p>
        <?php
          echo $register_failed_message . "<br>";
        ?>
      </p>

      <!-- Register Form -->
      <form class="login-form" method="post" action="register.php">
        <div class="inline-fields">
          <!-- Input First Name -->
          <div>
            <label for="input-first-name">First Name</label>
            <input type="text" name="input-first-name" id="input-first-name"/>
          </div>
          <!-- Input Last Name -->
          <div class="fix-margin">
            <label for="input-last-name">Last Name</label>
            <input type="text" name="input-last-name" id="input-last-name"/>
          </div>
        </div>
        <!-- Input Username -->
        <div>
          <label for="input-username" class="required">Username</label>
          <input type="text" name="input-username" id="input-username" required/>
        </div>
        <div class="inline-fields">
          <!-- Input Date of Birth -->
          <div>
            <label for="input-dob">Date of Birth</label>
            <input type="date" name="input-dob" id="input-dob"/>
          </div>
          <!-- Upload Profile Photo -->
          <div class="fix-margin">
            <label for="upload-pfp">Upload Profile Photo (.jpg, .jpeg, .png, .gif)</label>
            <input type="file" name="upload-pfp" id="upload-pfp"/>
          </div>
        </div>
        <!-- Input Password -->
        <div>
          <label for="input-password" class="required">Password</label>
          <input type="password" name="input-password" id="input-password" required>
        </div>
        <!-- Confirm Password -->
        <div>
          <label for="input-confirm-password" class="required">Confirm Password</label>
          <input type="password" name="input-confirm-password" id="input-confirm-password" required>
        </div>
        <!-- Register Button -->
        <input type="submit" name="register-button" id="register-button" value="Register"/>
      </form>

      <!-- Login Prompt -->
      <h4>Already have an account?</h4>
      <a href="login.php"><b>Login →</b></a>
    </div>
  </div>
</body>
</html>