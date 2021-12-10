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

$register_failed_message = "";

if(isset($_POST['register-button']) && $_POST['register-button'] == "Register") {
  $fname = stripslashes(trim(htmlspecialchars($_POST['input-first-name'])));
  $lname = stripslashes(trim(htmlspecialchars($_POST['input-last-name'])));
  $username = stripslashes(trim(htmlspecialchars($_POST['input-username'])));
  $dob = stripslashes(trim(htmlspecialchars($_POST['input-dob'])));
  $password = stripslashes(trim(htmlspecialchars($_POST['input-password'])));
  $confirmed_password = stripslashes(trim(htmlspecialchars($_POST['input-confirm-password'])));
  $pfp = $_FILES['upload-pfp'];
  
  $prepared = $db->prepare("SELECT * from users WHERE (username = :username)");
  $prepared->execute(['username' => $username]);
  $result = $prepared->fetchAll();

  $file_size = $pfp['size'];
  $file_tmp = $pfp['tmp_name'];
  $file_type = $pfp['type'];
  @$file_ext = strtolower(end(explode('.', $pfp['name'])));
  $extensions = array("jpeg","jpg","png");

  if(empty($username)) $register_failed_message = "Please enter a username.";
  else if(empty($password)) $register_failed_message = "Please enter a password.";
  else if($password != $confirmed_password) $register_failed_message = "Passwords do not match.";
  else if(count($result) > 0) $register_failed_message = "User is already registered.";
  else if(!empty($pfp['name']) && in_array($file_ext, $extensions) === false) {
    $register_failed_message = "File extension not allowed, please upload a JPG, JPEG, or PNG file.";
  }
  else if(!empty($pfp['name']) && $file_size > 2097152) {
    $register_failed_message = "Uploaded file is too big (needs to be less than 2MB).";
  }
  else {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $prepared = $db->prepare("INSERT INTO users (username, password, type) VALUES (:username, :password, :type)");
    $prepared->execute(['username' => $username, 'password' => $hashed_password, 'type' => 0]);
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
    if(!empty($pfp['name'])) {
      $file_name = $user_id . '.' . $file_ext;
      move_uploaded_file($file_tmp,"uploads/".$file_name);
      $prepared = $db->prepare("UPDATE users SET filepath = :filep WHERE user_id = :user_id;");
      $prepared->execute(['filep' =>"uploads/".$file_name , 'user_id' => $user_id]);
    }

    $finish = $prepared->fetchAll();

    header("Location: login.php");
    exit();
  }
}

$nav_login_style = "\"display: block;\"";
$nav_profile_style = "\"display: none;\"";
$pfp_src = "Resources/assets/profile.png";

if(!empty($_SESSION['filepath'])) {
  $pfp_src = $_SESSION['filepath'];
}

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
  $nav_login_style = "\"display: none;\"";
  $nav_profile_style = "\"display: block;\"";
}
else {
  $nav_login_style = "\"display: block;\"";
  $nav_profile_style = "\"display: none;\"";
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
  <title>Create a JAM Account</title>
</head>

<body>
  <!-- Navbar -->
  <nav>
    <a class="active" href="./index.php">Home</a>
    <div class="right">
      <!-- Links -->
      <a href="./dashboard.php">Dashboard</a>
      <a href="./login.php" style=<?php echo $nav_login_style; ?>>Login</a>
      <button id="profile-icon-button" onclick="toggleProfile()" style=<?php echo $nav_profile_style; ?>>
        <img class="profile-icon" src=<?php echo $pfp_src ?> alt="User Profile Picture"/>
      </button>
    </div>
  </nav>

  <!-- Profile section -->
  <div id="profile-menu" style="display: none;">
    <div id="profile-overview">
      <img class="profile-icon" src=<?php echo $pfp_src ?> alt="User Profile Picture"/>
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
      <form id="logout-form" method="post" action="logout.php">
        <input type="submit" class="btn btn-jam" name="logout" id="logout" value="Logout"/>
      </form>
    </div>
  </div>

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
      <form class="login-form" method="POST" action="register.php" enctype="multipart/form-data">
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
            <label for="upload-pfp">Upload Profile Photo (.jpg, .jpeg, .png)</label>
            <input type="file" name="upload-pfp" id="upload-pfp" value=""/>
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

  <!-- Footer -->
  <footer>
    <p class="copyright text-white">© JAM 2021</p>
  </footer>
</body>
</html>
