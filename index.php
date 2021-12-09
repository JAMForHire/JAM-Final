<?php
session_start();
include_once('config.php');
require_once("./components/jar.php");

try {
  $db = new PDO('mysql:host=localhost;dbname=JAM', $db_username, $db_password);
}
catch(PDOException $e) {
  echo "Failed to connect to JAM database: " . $e->getMessage() . "<br>";
  exit();
}

$nav_login_style = "\"display: block;\"";
$nav_profile_style = "\"display: none;\"";
$pfp_src = "Resources/assets/profile.png";
$opportunity_reminder_style = "\"display: none;\"";
$close_to_due_style = "\"display: block;\"";
$overdue_style = "\"display: none;\"";

if(!empty($_SESSION['filepath'])) {
  $pfp_src = $_SESSION['filepath'];
}

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
  $nav_login_style = "\"display: none;\"";
  $nav_profile_style = "\"display: block;\"";

  $jars = get_jars($db, $_SESSION['user_id'], 1, 0);

  // check for overdue opportunities
  $close_to_due = 0;
  foreach($jars as $jar) {
    $current_time = time() - 5*60*60;
    $days_till_due = (strtotime($jar['date']) - $current_time) / (60*60*24);
    if($days_till_due < 0) {
      $opportunity_reminder_style = "\"display: block;\"";
      $close_to_due_style = "\"display: none;\"";
      $overdue_style = "\"display: block;\"";
      $close_to_due = 1;
      break;
    }
  }
  if(!$close_to_due) {
    // check for close to due opportunities
    foreach($jars as $jar) {
      $days_till_due = (strtotime($jar['date']) - $current_time) / (60*60*24);
      if($days_till_due < 3) {
        $opportunity_reminder_style = "\"display: block;\"";
        $close_to_due_style = "\"display: block;\"";
        $overdue_style = "\"display: none;\"";
        break;
      }
    }
  }
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
  <title>JAM Homepage</title>
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
        <img class="profile-icon rounded-circle" src=<?php echo $pfp_src ?> alt="User Profile Picture"/>
      </button>
    </div>
  </nav>

  <!-- Profile section -->
  <div id="profile-menu" style="display: none;">
    <div id="profile-overview">
      <img class="profile-icon rounded-circle" src=<?php echo $pfp_src ?> alt="User Profile Picture"/>
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

  <!-- Opportunity Reminder section -->
  <div id="opportunity-reminder" style=<?php echo $opportunity_reminder_style; ?>>
    <h4 style=<?php echo $close_to_due_style ?>>Reminder: You have at least one opportunity close to its due date! Check your dashboard now!</h4>
    <h4 style=<?php echo $overdue_style ?>>Oh no! At least one of your opportunities has expired. Check your dashboard now.</h4>
  </div>
  
  <!-- Hero section -->
  <section class="container col-xxl-16 px-4 py-5 ">
    <div class="row flex-lg-row align-items-center g-5 py-5">
      <!-- Content -->
      <div class="col-lg-6 d-flex flex-column align-items-lg-start align-items-center">
        <h1 class="fw-bold display-1">JAM</h1>
        <div class="accent mb-2"></div>
        <h1 class="display-6 mb-4">Job Application Manager</h1>
        <p class="lead text-left">JAM is a self application tracker that helps you stay organized, be productive, and reminds you when your applications are due</p>
        <div class="d-flex flex-lg-row flex-column gap-2 d-md-flex justify-content-md-start mt-3 text-sm-center">
          <button type="button" class="btn btn-jam btn-lg px-4 text-white" onclick="redirect('#about')">Learn More</button>
          <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="redirect('login.php')">Login</button>
        </div>
      </div>
      <!-- Logo -->
      <div class="col-lg-6 d-flex flex-row justify-content-center">
        <img src="./Resources/assets/logo.png" class="d-block mx-lg-auto img-fluid mb-5" alt="Logo" width="450" height="450">
      </div>
    </div>
    <!-- Chevron -->
    <a href="#about">
      <div class="d-flex justify-content-center">
        <img class="chevron" src="./Resources/assets/chevron_down.svg" alt="chevron"/>
      </div>
    </a>
  </section>

  <!-- About Section -->
  <section class="bg-jam text-center h-100 w-100 p-3 pb-5" id="about">
    <h2 class="display-2 text-white mb-4">About</h2>
    <div class="container pb-5">
      <div class="row">
        <!-- Individual Section -->
        <div class="col-sm">
          <img class="main_img" src="./Resources/assets/undraw_organize.svg" alt="organized" />
          <h3 class="text-white">Stay Organized</h3>
          <div class="text-white">
            JAM will help organize all your Opportunities for you 
            and can hold information about those specific opportunities.
          </div>
        </div>
        <!-- Individual Section -->
        <div class="col-sm">
          <img class="main_img" src="./Resources/assets/undraw_time.svg" alt="organized" />
          <h3 class="text-white">Time is Key</h3>
          <div class="text-white">
            JAM reminds the user when an application is going to be 
            overdue or has not been started if it is getting close to the
            application deadline.
          </div>
        </div>
        <!-- Individual Section -->
        <div class="col-sm">
          <img class="main_img_2" src="./Resources/assets/undraw_growth.svg" alt="organized" />
          <h3 class="text-white">Maximize Potential</h3>
          <div class="text-white">
            With using JAM with all of their reminders, it keeps the user
            on track and makes sure they can complete as many applications
            as they would like to before their due dates.
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bottom Section -->
  <section class="d-flex flex-column align-items-center my-5">
    <h2 class="display-6 mb-5">Join Today!</h2>
    <!-- Button row -->
    <div class="d-flex mt-1 mb-5 h-100 w-100 gap-5 justify-content-center">
      <div class="d-flex flex-column align-items-center">
        <img src="./Resources/assets/undraw_login.svg" class="main_img" />
        <div><button type="button" class="btn btn-lg btn-jam text-white" onclick="redirect('./login.php')">Login</button></div>
      </div>
      <div class="d-flex flex-column align-items-center">
        <img src="./Resources/assets/undraw_add_user.svg" class="main_img_2" />
        <div><button type="button" class="btn btn-lg btn-jam text-white" onclick="redirect('./register.php')">Register</button></div>
      </div>
    </div>
  </section>

  <footer>
    <p class="copyright">© JAM 2021</p>
  </footer>
</body>
</html>